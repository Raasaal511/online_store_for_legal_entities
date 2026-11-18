<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bootstrap;
use App\Domain\Application\ApplicationFilter;
use App\Domain\Application\ApplicationStatus;
use App\Http\Request;
use App\Http\Response;
use App\Http\View;

final class AdminController
{
    public function __construct(private readonly View $view)
    {
    }

    public function loginForm(Request $request): Response
    {
        if (Bootstrap::auth()->check()) {
            return Response::redirect('/admin');
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        $html = $this->view->render('admin/login', [
            'pageTitle' => 'Вход в кабинет — СнабПлюс',
            'error' => $error,
            'csrfToken' => Bootstrap::csrf()->token(),
            'appName' => 'СнабПлюс',
            'isAdmin' => true,
        ], 'layouts/admin');

        return Response::html($html);
    }

    public function login(Request $request): Response
    {
        $body = $request->body();
        $csrf = Bootstrap::csrf();

        if (!$csrf->isValid(isset($body['_token']) ? (string) $body['_token'] : null)) {
            $_SESSION['login_error'] = 'Сессия устарела. Попробуйте ещё раз.';
            return Response::redirect('/admin/login');
        }

        $login = trim((string) ($body['login'] ?? ''));
        $password = (string) ($body['password'] ?? '');

        if (!Bootstrap::auth()->attempt($login, $password)) {
            $_SESSION['login_error'] = 'Неверный логин или пароль.';
            return Response::redirect('/admin/login');
        }

        return Response::redirect('/admin');
    }

    public function logout(Request $request): Response
    {
        Bootstrap::auth()->logout();
        return Response::redirect('/admin/login');
    }

    public function index(Request $request): Response
    {
        if (!Bootstrap::auth()->check()) {
            return Response::redirect('/admin/login');
        }

        $filter = ApplicationFilter::fromArray($request->query());
        $applications = Bootstrap::applicationService()->list($filter);
        $flash = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);

        $html = $this->view->render('admin/index', [
            'pageTitle' => 'Заявки — СнабПлюс',
            'applications' => $applications,
            'statuses' => ApplicationStatus::all(),
            'filter' => $filter,
            'query' => $request->query(),
            'flash' => $flash,
            'csrfToken' => Bootstrap::csrf()->token(),
            'appName' => 'СнабПлюс',
            'isAdmin' => true,
        ], 'layouts/admin');

        return Response::html($html);
    }

    public function updateStatus(Request $request): Response
    {
        if (!Bootstrap::auth()->check()) {
            return Response::redirect('/admin/login');
        }

        $body = $request->body();
        $csrf = Bootstrap::csrf();

        if (!$csrf->isValid(isset($body['_token']) ? (string) $body['_token'] : null)) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Сессия устарела. Обновите страницу.'];
            return Response::redirect('/admin');
        }

        $id = (int) ($body['id'] ?? 0);
        $status = ApplicationStatus::tryFrom((string) ($body['status'] ?? ''));

        if ($id <= 0 || $status === null) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Некорректные данные статуса.'];
            return Response::redirect($this->redirectWithQuery($request));
        }

        $updated = Bootstrap::applicationService()->changeStatus($id, $status);

        $_SESSION['admin_flash'] = $updated
            ? ['type' => 'success', 'message' => 'Статус заявки обновлён.']
            : ['type' => 'error', 'message' => 'Заявка не найдена.'];

        return Response::redirect($this->redirectWithQuery($request));
    }

    private function redirectWithQuery(Request $request): string
    {
        $map = [
            'filter_status' => 'status',
            'q' => 'q',
            'date_from' => 'date_from',
            'date_to' => 'date_to',
        ];

        $query = [];
        foreach ($map as $inputKey => $queryKey) {
            $value = $request->input($inputKey);
            if (is_string($value) && $value !== '') {
                $query[$queryKey] = $value;
            }
        }

        if ($query === []) {
            return '/admin';
        }

        return '/admin?' . http_build_query($query);
    }
}
