<?php

declare(strict_types=1);

namespace App\Domain\Application;

enum ApplicationStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Новая',
            self::InProgress => 'В работе',
            self::Done => 'Закрыта',
            self::Rejected => 'Отклонена',
        };
    }

    /**
     * @return list<self>
     */
    public static function all(): array
    {
        return self::cases();
    }

    public static function tryFromMixed(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
