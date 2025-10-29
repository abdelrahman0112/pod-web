<?php

namespace App;

enum LocationType: string
{
    case REMOTE = 'remote';
    case ON_SITE = 'on-site';
    case HYBRID = 'hybrid';

    public function getLabel(): string
    {
        return match ($this) {
            self::REMOTE => 'Remote',
            self::ON_SITE => 'On-Site',
            self::HYBRID => 'Hybrid',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
