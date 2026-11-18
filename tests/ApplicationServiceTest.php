<?php

declare(strict_types=1);

namespace Tests;

use App\Database\ConnectionFactory;
use App\Domain\Application\ApplicationFilter;
use App\Domain\Application\ApplicationStatus;
use App\Repository\PdoApplicationRepository;
use App\Service\ApplicationService;
use PHPUnit\Framework\TestCase;
use Tests\Support\SqliteSchema;

final class ApplicationServiceTest extends TestCase
{
    private ApplicationService $service;

    protected function setUp(): void
    {
        $pdo = ConnectionFactory::createSqliteMemory();
        SqliteSchema::migrate($pdo);
        $this->service = new ApplicationService(new PdoApplicationRepository($pdo));
    }

    public function testCreatesApplication(): void
    {
        $result = $this->service->submit([
            'company_name' => 'ООО Тест',
            'contact_name' => 'Алексей',
            'phone' => '+7 999 111-22-33',
            'email' => 'a@test.ru',
        ]);

        self::assertTrue($result['success']);
        self::assertNotNull($result['application']);
        self::assertSame(ApplicationStatus::New, $result['application']->status);
        self::assertSame('ООО Тест', $result['application']->companyName);
    }

    public function testDoesNotCreateInvalidApplication(): void
    {
        $result = $this->service->submit([
            'company_name' => '',
            'contact_name' => '',
            'phone' => '',
            'email' => 'bad',
        ]);

        self::assertFalse($result['success']);
        self::assertNull($result['application']);
        self::assertNotEmpty($result['errors']);
    }

    public function testFiltersByStatusAndQuery(): void
    {
        $this->service->submit([
            'company_name' => 'ООО Альфа',
            'contact_name' => 'Мария',
            'phone' => '+7 900 111-11-11',
            'email' => 'alpha@test.ru',
        ]);

        $second = $this->service->submit([
            'company_name' => 'ООО Бета',
            'contact_name' => 'Игорь',
            'phone' => '+7 900 222-22-22',
            'email' => 'beta@test.ru',
        ]);

        self::assertTrue($second['success']);
        $this->service->changeStatus((int) $second['application']->id, ApplicationStatus::InProgress);

        $byStatus = $this->service->list(new ApplicationFilter(status: ApplicationStatus::InProgress));
        self::assertCount(1, $byStatus);
        self::assertSame('ООО Бета', $byStatus[0]->companyName);

        $byQuery = $this->service->list(new ApplicationFilter(query: 'Альфа'));
        self::assertCount(1, $byQuery);
        self::assertSame('ООО Альфа', $byQuery[0]->companyName);
    }

    public function testUpdatesStatus(): void
    {
        $created = $this->service->submit([
            'company_name' => 'ООО Гамма',
            'contact_name' => 'Ольга',
            'phone' => '+7 900 333-33-33',
            'email' => 'gamma@test.ru',
        ]);

        $id = (int) $created['application']->id;
        self::assertTrue($this->service->changeStatus($id, ApplicationStatus::Done));

        $found = $this->service->find($id);
        self::assertNotNull($found);
        self::assertSame(ApplicationStatus::Done, $found->status);
    }
}
