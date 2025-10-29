<?php

namespace App;

enum HackathonFormat: string
{
    case ONLINE = 'online';
    case ON_SITE = 'on-site';
    case HYBRID = 'hybrid';

    public function getLabel(): string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::ON_SITE => 'In-Person',
            self::HYBRID => 'Hybrid',
        };
    }

    public function requiresLocation(): bool
    {
        return match ($this) {
            self::ONLINE => false,
            self::ON_SITE => true,
            self::HYBRID => true,
        };
    }

    public static function getOptions(): array
    {
        return collect(self::cases())->map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->getLabel(),
        ])->toArray();
    }
}
