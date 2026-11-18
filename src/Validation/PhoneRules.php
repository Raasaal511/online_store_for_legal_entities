<?php

declare(strict_types=1);

namespace App\Validation;

final class PhoneRules
{
    /**
     * @return array<string, array{label: string, digits: int, placeholder: string}>
     */
    public static function countries(): array
    {
        return [
            '+7' => ['label' => '+7 Россия / Казахстан', 'digits' => 10, 'placeholder' => '(999) 123-45-67'],
            '+375' => ['label' => '+375 Беларусь', 'digits' => 9, 'placeholder' => '29 123-45-67'],
            '+380' => ['label' => '+380 Украина', 'digits' => 9, 'placeholder' => '67 123-45-67'],
            '+998' => ['label' => '+998 Узбекистан', 'digits' => 9, 'placeholder' => '90 123-45-67'],
            '+996' => ['label' => '+996 Кыргызстан', 'digits' => 9, 'placeholder' => '700 123-456'],
            '+992' => ['label' => '+992 Таджикистан', 'digits' => 9, 'placeholder' => '90 123-45-67'],
            '+993' => ['label' => '+993 Туркменистан', 'digits' => 8, 'placeholder' => '65 12-34-56'],
            '+994' => ['label' => '+994 Азербайджан', 'digits' => 9, 'placeholder' => '50 123-45-67'],
            '+995' => ['label' => '+995 Грузия', 'digits' => 9, 'placeholder' => '555 12-34-56'],
            '+374' => ['label' => '+374 Армения', 'digits' => 8, 'placeholder' => '91 123456'],
        ];
    }

    public static function isAllowedCode(string $code): bool
    {
        return isset(self::countries()[$code]);
    }

    public static function expectedDigits(string $code): int
    {
        return self::countries()[$code]['digits'] ?? 10;
    }

    /**
     * @return array{code: string, number: string}
     */
    public static function split(string $phone): array
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        foreach (array_keys(self::countries()) as $code) {
            $codeDigits = ltrim($code, '+');
            if (str_starts_with($digits, $codeDigits)) {
                return [
                    'code' => $code,
                    'number' => substr($digits, strlen($codeDigits)),
                ];
            }
        }

        if (str_starts_with($digits, '8') && strlen($digits) === 11) {
            return ['code' => '+7', 'number' => substr($digits, 1)];
        }

        return ['code' => '+7', 'number' => $digits];
    }

    public static function format(string $code, string $nationalDigits): string
    {
        $nationalDigits = preg_replace('/\D+/', '', $nationalDigits) ?? '';

        if ($code === '+7' && strlen($nationalDigits) === 10) {
            return sprintf(
                '+7 (%s) %s-%s-%s',
                substr($nationalDigits, 0, 3),
                substr($nationalDigits, 3, 3),
                substr($nationalDigits, 6, 2),
                substr($nationalDigits, 8, 2)
            );
        }

        return trim($code . ' ' . $nationalDigits);
    }
}
