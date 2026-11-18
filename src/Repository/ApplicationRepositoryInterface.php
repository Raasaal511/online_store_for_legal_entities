<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationFilter;
use App\Domain\Application\ApplicationStatus;

interface ApplicationRepositoryInterface
{
    public function create(
        string $companyName,
        string $contactName,
        string $phone,
        string $email,
    ): Application;

    public function findById(int $id): ?Application;

    /**
     * @return list<Application>
     */
    public function findByFilter(ApplicationFilter $filter): array;

    public function updateStatus(int $id, ApplicationStatus $status): bool;
}
