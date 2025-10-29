<?php

namespace App;

enum ExperienceLevel: string
{
    case ENTRY = 'entry';
    case JUNIOR = 'junior';
    case MID = 'mid';
    case SENIOR = 'senior';
    case EXPERT = 'expert';

    public function getLabel(): string
    {
        return match ($this) {
            self::ENTRY => 'Entry Level',
            self::JUNIOR => 'Junior',
            self::MID => 'Mid Level',
            self::SENIOR => 'Senior',
            self::EXPERT => 'Expert',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }
}
