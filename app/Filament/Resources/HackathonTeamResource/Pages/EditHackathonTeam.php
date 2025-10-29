<?php

namespace App\Filament\Resources\HackathonTeamResource\Pages;

use App\Filament\Resources\HackathonTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHackathonTeam extends EditRecord
{
    protected static string $resource = HackathonTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
