<?php

namespace App\Filament\Resources\HackathonTeamMemberResource\Pages;

use App\Filament\Resources\HackathonTeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHackathonTeamMember extends EditRecord
{
    protected static string $resource = HackathonTeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
