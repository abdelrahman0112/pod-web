<?php

namespace App;

enum HackathonLocation: string
{
    case ONLINE = 'Online';
    case CAIRO = 'Cairo, Egypt';
    case ALEXANDRIA = 'Alexandria, Egypt';
    case GIZA = 'Giza, Egypt';
    case SHARM_EL_SHEIKH = 'Sharm El Sheikh, Egypt';
    case LUXOR = 'Luxor, Egypt';
    case ASWAN = 'Aswan, Egypt';
    case HURGHADA = 'Hurghada, Egypt';
    case MANSOURA = 'Mansoura, Egypt';
    case TANTA = 'Tanta, Egypt';
    case ZAGAZIG = 'Zagazig, Egypt';
    case ISMAILIA = 'Ismailia, Egypt';
    case PORT_SAID = 'Port Said, Egypt';
    case SUEZ = 'Suez, Egypt';
    case MINYA = 'Minya, Egypt';
    case ASYUT = 'Asyut, Egypt';

    public function getLabel(): string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->getLabel(),
        ])->toArray();
    }

    public static function getCities(): array
    {
        return collect(self::cases())
            ->filter(fn ($case) => $case !== self::ONLINE)
            ->mapWithKeys(fn ($case) => [
                $case->value => $case->getLabel(),
            ])
            ->toArray();
    }
}
