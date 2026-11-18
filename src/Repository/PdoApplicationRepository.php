<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationFilter;
use App\Domain\Application\ApplicationStatus;
use PDO;

final class PdoApplicationRepository implements ApplicationRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function create(
        string $companyName,
        string $contactName,
        string $phone,
        string $email,
    ): Application {
        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');

        $statement = $this->pdo->prepare(
            'INSERT INTO applications (company_name, contact_name, phone, email, status, created_at, updated_at)
             VALUES (:company_name, :contact_name, :phone, :email, :status, :created_at, :updated_at)'
        );

        $statement->execute([
            'company_name' => $companyName,
            'contact_name' => $contactName,
            'phone' => $phone,
            'email' => $email,
            'status' => ApplicationStatus::New->value,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $id = (int) $this->pdo->lastInsertId();
        $application = $this->findById($id);

        if ($application === null) {
            throw new \RuntimeException('Failed to load created application.');
        }

        return $application;
    }

    public function findById(int $id): ?Application
    {
        $statement = $this->pdo->prepare('SELECT * FROM applications WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ? Application::fromRow($row) : null;
    }

    public function findByFilter(ApplicationFilter $filter): array
    {
        $sql = 'SELECT * FROM applications WHERE 1=1';
        $params = [];

        if ($filter->status !== null) {
            $sql .= ' AND status = :status';
            $params['status'] = $filter->status->value;
        }

        if ($filter->query !== null) {
            $sql .= ' AND (
                company_name LIKE :query
                OR contact_name LIKE :query
                OR phone LIKE :query
                OR email LIKE :query
            )';
            $params['query'] = '%' . $filter->query . '%';
        }

        if ($filter->dateFrom !== null) {
            $sql .= ' AND created_at >= :date_from';
            $params['date_from'] = $filter->dateFrom->format('Y-m-d H:i:s');
        }

        if ($filter->dateTo !== null) {
            $sql .= ' AND created_at <= :date_to';
            $params['date_to'] = $filter->dateTo->format('Y-m-d H:i:s');
        }

        $sql .= ' ORDER BY created_at DESC, id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $items = [];
        foreach ($statement->fetchAll() as $row) {
            $items[] = Application::fromRow($row);
        }

        return $items;
    }

    public function updateStatus(int $id, ApplicationStatus $status): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE applications
             SET status = :status, updated_at = :updated_at
             WHERE id = :id'
        );

        $statement->execute([
            'status' => $status->value,
            'updated_at' => (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
            'id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }
}
