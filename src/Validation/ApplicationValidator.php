<?php

declare(strict_types=1);

namespace App\Validation;

final class ApplicationValidator
{
    /**
     * @param array<string, mixed> $input
     * @return array{valid: bool, errors: array<string, string>, data: array<string, string>}
     */
    public function validate(array $input): array
    {
        $data = [
            'company_name' => trim((string) ($input['company_name'] ?? '')),
            'contact_name' => trim((string) ($input['contact_name'] ?? '')),
            'phone' => trim((string) ($input['phone'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
        ];

        $errors = [];

        if ($data['company_name'] === '' || mb_strlen($data['company_name']) < 2) {
            $errors['company_name'] = 'Укажите название компании.';
        } elseif (mb_strlen($data['company_name']) > 255) {
            $errors['company_name'] = 'Название компании слишком длинное.';
        }

        if ($data['contact_name'] === '' || mb_strlen($data['contact_name']) < 2) {
            $errors['contact_name'] = 'Укажите имя контактного лица.';
        } elseif (mb_strlen($data['contact_name']) > 150) {
            $errors['contact_name'] = 'Имя слишком длинное.';
        }

        if ($data['phone'] === '') {
            $errors['phone'] = 'Укажите телефон.';
        } elseif (!$this->isValidPhone($data['phone'])) {
            $errors['phone'] = 'Введите корректный номер телефона.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Укажите электронную почту.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный email.';
        } elseif (mb_strlen($data['email']) > 190) {
            $errors['email'] = 'Email слишком длинный.';
        }

        return [
            'valid' => $errors === [],
            'errors' => $errors,
            'data' => $data,
        ];
    }

    private function isValidPhone(string $phone): bool
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        return strlen($digits) >= 10 && strlen($digits) <= 15;
    }
}
