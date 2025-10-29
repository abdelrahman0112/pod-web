<?php

namespace App\Filament\Resources\HackathonTeamResource\Pages;

use App\Filament\Resources\HackathonTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHackathonTeams extends ListRecords
{
    protected static string $resource = HackathonTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
