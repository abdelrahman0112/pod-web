<?php

namespace App;

enum JobStatus: string
{
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::CLOSED => 'Closed',
            self::ARCHIVED => 'Archived',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::CLOSED => 'red',
            self::ARCHIVED => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
