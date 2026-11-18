<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bootstrap;
use App\Http\Request;
use App\Http\Response;
use App\Http\View;

final class LandingController
{
    public function __construct(
        private readonly View $view,
        private readonly string $basePath,
    ) {
    }

    public function index(Request $request): Response
    {
        $csrf = Bootstrap::csrf();
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        $old = $_SESSION['old_input'] ?? [
            'company_name' => '',
            'contact_name' => '',
            'phone' => '',
            'phone_code' => '+7',
            'phone_number' => '',
            'email' => '',
        ];
        $errors = $_SESSION['form_errors'] ?? [];
        unset($_SESSION['old_input'], $_SESSION['form_errors']);

        $html = $this->view->render('landing/index', [
            'pageTitle' => 'Для юридических лиц — СнабПлюс',
            'csrfToken' => $csrf->token(),
            'flashSuccess' => $flashSuccess,
            'old' => $old,
            'errors' => $errors,
            'appName' => 'СнабПлюс',
        ]);

        return Response::html($html);
    }

    public function submit(Request $request): Response
    {
        $csrf = Bootstrap::csrf();
        $body = $request->body();

        if (!$csrf->isValid(isset($body['_token']) ? (string) $body['_token'] : null)) {
            $_SESSION['form_errors'] = ['_form' => 'Сессия устарела. Обновите страницу и отправьте заявку ещё раз.'];
            $_SESSION['old_input'] = $this->extractOld($body);
            return Response::redirect('/#request');
        }

        // Honeypot: bots fill hidden field
        if (trim((string) ($body['website'] ?? '')) !== '') {
            $_SESSION['flash_success'] = 'Заявка отправлена. Мы свяжемся с вами в ближайшее время.';
            return Response::redirect('/#request');
        }

        $result = Bootstrap::applicationService()->submit($body);

        if (!$result['success']) {
            $_SESSION['form_errors'] = $result['errors'];
            $_SESSION['old_input'] = $result['data'];
            return Response::redirect('/#request');
        }

        $_SESSION['flash_success'] = 'Заявка принята. Менеджер свяжется с вами в рабочие часы.';
        return Response::redirect('/#request');
    }

    /**
     * @param array<string, mixed> $body
     * @return array<string, string>
     */
    private function extractOld(array $body): array
    {
        return [
            'company_name' => trim((string) ($body['company_name'] ?? '')),
            'contact_name' => trim((string) ($body['contact_name'] ?? '')),
            'phone' => trim((string) ($body['phone'] ?? '')),
            'phone_code' => trim((string) ($body['phone_code'] ?? '+7')),
            'phone_number' => trim((string) ($body['phone_number'] ?? '')),
            'email' => trim((string) ($body['email'] ?? '')),
        ];
    }
}
