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
        $companyName = trim((string) ($input['company_name'] ?? ''));
        $contactName = trim((string) ($input['contact_name'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));

        $code = trim((string) ($input['phone_code'] ?? ''));
        $number = preg_replace('/\D+/', '', (string) ($input['phone_number'] ?? '')) ?? '';

        if ($code === '' && $number === '' && trim((string) ($input['phone'] ?? '')) !== '') {
            $split = PhoneRules::split((string) $input['phone']);
            $code = $split['code'];
            $number = $split['number'];
        }

        if ($code === '') {
            $code = '+7';
        }

        $phone = PhoneRules::format($code, $number);

        $data = [
            'company_name' => $companyName,
            'contact_name' => $contactName,
            'phone' => $phone,
            'phone_code' => $code,
            'phone_number' => $number,
            'email' => $email,
        ];

        $errors = [];

        if ($companyName === '' || mb_strlen($companyName) < 2) {
            $errors['company_name'] = 'Укажите название компании.';
        } elseif (mb_strlen($companyName) > 255) {
            $errors['company_name'] = 'Название компании слишком длинное.';
        }

        if ($contactName === '' || mb_strlen($contactName) < 2) {
            $errors['contact_name'] = 'Укажите имя контактного лица.';
        } elseif (mb_strlen($contactName) > 150) {
            $errors['contact_name'] = 'Имя слишком длинное.';
        }

        if (!PhoneRules::isAllowedCode($code)) {
            $errors['phone'] = 'Выберите код страны.';
        } elseif ($number === '') {
            $errors['phone'] = 'Укажите телефон.';
        } elseif (strlen($number) !== PhoneRules::expectedDigits($code)) {
            $expected = PhoneRules::expectedDigits($code);
            $errors['phone'] = "Введите номер полностью: {$expected} цифр после кода страны.";
        }

        if ($email === '') {
            $errors['email'] = 'Укажите электронную почту.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный email.';
        } elseif (mb_strlen($email) > 190) {
            $errors['email'] = 'Email слишком длинный.';
        }

        return [
            'valid' => $errors === [],
            'errors' => $errors,
            'data' => $data,
        ];
    }
}
