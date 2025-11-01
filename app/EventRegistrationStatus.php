<?php

namespace App;

enum EventRegistrationStatus: string
{
    case CONFIRMED = 'confirmed';
    case WAITLISTED = 'waitlisted';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::CONFIRMED => 'Confirmed',
            self::WAITLISTED => 'Waitlisted',
            self::CANCELLED => 'Cancelled',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
