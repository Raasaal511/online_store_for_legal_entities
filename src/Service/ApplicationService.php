<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationFilter;
use App\Domain\Application\ApplicationStatus;
use App\Repository\ApplicationRepositoryInterface;
use App\Validation\ApplicationValidator;

final class ApplicationService
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $repository,
        private readonly ApplicationValidator $validator = new ApplicationValidator(),
    ) {
    }

    /**
     * @param array<string, mixed> $input
     * @return array{success: bool, errors: array<string, string>, application: ?Application, data: array<string, string>}
     */
    public function submit(array $input): array
    {
        $result = $this->validator->validate($input);

        if (!$result['valid']) {
            return [
                'success' => false,
                'errors' => $result['errors'],
                'application' => null,
                'data' => $result['data'],
            ];
        }

        $application = $this->repository->create(
            $result['data']['company_name'],
            $result['data']['contact_name'],
            $result['data']['phone'],
            $result['data']['email'],
        );

        return [
            'success' => true,
            'errors' => [],
            'application' => $application,
            'data' => $result['data'],
        ];
    }

    /**
     * @return list<Application>
     */
    public function list(ApplicationFilter $filter): array
    {
        return $this->repository->findByFilter($filter);
    }

    public function find(int $id): ?Application
    {
        return $this->repository->findById($id);
    }

    public function changeStatus(int $id, ApplicationStatus $status): bool
    {
        return $this->repository->updateStatus($id, $status);
    }
}
