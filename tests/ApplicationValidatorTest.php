<?php

declare(strict_types=1);

namespace Tests;

use App\Validation\ApplicationValidator;
use PHPUnit\Framework\TestCase;

final class ApplicationValidatorTest extends TestCase
{
    private ApplicationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ApplicationValidator();
    }

    public function testRejectsEmptyFields(): void
    {
        $result = $this->validator->validate([]);

        self::assertFalse($result['valid']);
        self::assertArrayHasKey('company_name', $result['errors']);
        self::assertArrayHasKey('contact_name', $result['errors']);
        self::assertArrayHasKey('phone', $result['errors']);
        self::assertArrayHasKey('email', $result['errors']);
    }

    public function testRejectsInvalidEmailAndPhone(): void
    {
        $result = $this->validator->validate([
            'company_name' => 'ООО Тест',
            'contact_name' => 'Иван',
            'phone_code' => '+7',
            'phone_number' => '123',
            'email' => 'not-an-email',
        ]);

        self::assertFalse($result['valid']);
        self::assertArrayHasKey('phone', $result['errors']);
        self::assertArrayHasKey('email', $result['errors']);
    }

    public function testRejectsTooLongPhoneNumber(): void
    {
        $result = $this->validator->validate([
            'company_name' => 'ООО Тест',
            'contact_name' => 'Иван',
            'phone_code' => '+7',
            'phone_number' => '904320493420340934204',
            'email' => 'a@test.ru',
        ]);

        self::assertFalse($result['valid']);
        self::assertArrayHasKey('phone', $result['errors']);
    }

    public function testAcceptsValidPayloadWithCode(): void
    {
        $result = $this->validator->validate([
            'company_name' => 'ООО «СеверТрейд»',
            'contact_name' => 'Ирина Ковалёва',
            'phone_code' => '+7',
            'phone_number' => '9214451208',
            'email' => 'i.kovaleva@severtrade.ru',
        ]);

        self::assertTrue($result['valid']);
        self::assertSame([], $result['errors']);
        self::assertSame('+7 (921) 445-12-08', $result['data']['phone']);
    }

    public function testAcceptsLegacyFullPhone(): void
    {
        $result = $this->validator->validate([
            'company_name' => 'ООО «СеверТрейд»',
            'contact_name' => 'Ирина Ковалёва',
            'phone' => '+7 (921) 445-12-08',
            'email' => 'i.kovaleva@severtrade.ru',
        ]);

        self::assertTrue($result['valid']);
        self::assertSame('+7 (921) 445-12-08', $result['data']['phone']);
    }
}
