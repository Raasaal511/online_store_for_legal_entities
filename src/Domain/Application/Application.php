<?php

declare(strict_types=1);

namespace App\Domain\Application;

final class Application
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $companyName,
        public readonly string $contactName,
        public readonly string $phone,
        public readonly string $email,
        public readonly ApplicationStatus $status,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            companyName: (string) $row['company_name'],
            contactName: (string) $row['contact_name'],
            phone: (string) $row['phone'],
            email: (string) $row['email'],
            status: ApplicationStatus::from((string) $row['status']),
            createdAt: new \DateTimeImmutable((string) $row['created_at']),
            updatedAt: new \DateTimeImmutable((string) $row['updated_at']),
        );
    }
}
