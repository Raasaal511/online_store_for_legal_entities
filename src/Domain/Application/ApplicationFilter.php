<?php

declare(strict_types=1);

namespace App\Domain\Application;

final class ApplicationFilter
{
    public function __construct(
        public readonly ?ApplicationStatus $status = null,
        public readonly ?string $query = null,
        public readonly ?\DateTimeImmutable $dateFrom = null,
        public readonly ?\DateTimeImmutable $dateTo = null,
    ) {
    }

    /**
     * @param array<string, mixed> $input
     */
    public static function fromArray(array $input): self
    {
        $statusRaw = trim((string) ($input['status'] ?? ''));
        $status = $statusRaw !== '' ? ApplicationStatus::tryFrom($statusRaw) : null;

        $query = trim((string) ($input['q'] ?? ''));
        $query = $query !== '' ? $query : null;

        $dateFrom = self::parseDate((string) ($input['date_from'] ?? ''), false);
        $dateTo = self::parseDate((string) ($input['date_to'] ?? ''), true);

        return new self($status, $query, $dateFrom, $dateTo);
    }

    private static function parseDate(string $value, bool $endOfDay): ?\DateTimeImmutable
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
        if ($date === false) {
            return null;
        }

        return $endOfDay
            ? $date->setTime(23, 59, 59)
            : $date->setTime(0, 0, 0);
    }
}
