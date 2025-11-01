<?php

namespace App\Filament\Resources\HackathonResource\Pages;

use App\Filament\Resources\HackathonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHackathon extends EditRecord
{
    protected static string $resource = HackathonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
