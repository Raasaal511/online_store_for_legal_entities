<?php

declare(strict_types=1);

namespace App\Domain\Application;

final class ApplicationFilter
{
    public const SORT_CREATED_AT = 'created_at';
    public const SORT_COMPANY = 'company_name';
    public const SORT_STATUS = 'status';
    public const SORT_ID = 'id';

    public const DIR_DESC = 'desc';
    public const DIR_ASC = 'asc';

    public function __construct(
        public readonly ?ApplicationStatus $status = null,
        public readonly ?string $query = null,
        public readonly ?\DateTimeImmutable $dateFrom = null,
        public readonly ?\DateTimeImmutable $dateTo = null,
        public readonly string $sort = self::SORT_CREATED_AT,
        public readonly string $direction = self::DIR_DESC,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function sortOptions(): array
    {
        return [
            self::SORT_CREATED_AT => 'По дате',
            self::SORT_COMPANY => 'По компании',
            self::SORT_STATUS => 'По статусу',
            self::SORT_ID => 'По ID',
        ];
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

        $sort = trim((string) ($input['sort'] ?? self::SORT_CREATED_AT));
        if (!array_key_exists($sort, self::sortOptions())) {
            $sort = self::SORT_CREATED_AT;
        }

        $direction = strtolower(trim((string) ($input['dir'] ?? self::DIR_DESC)));
        if (!in_array($direction, [self::DIR_ASC, self::DIR_DESC], true)) {
            $direction = self::DIR_DESC;
        }

        return new self($status, $query, $dateFrom, $dateTo, $sort, $direction);
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
