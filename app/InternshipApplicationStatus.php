<?php

namespace App;

enum InternshipApplicationStatus: string
{
    case PENDING = 'pending';
    case REVIEWED = 'reviewed';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::REVIEWED => 'Reviewed',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
