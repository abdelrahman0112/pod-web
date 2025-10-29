<?php

namespace App\Filament\Resources\HackathonResource\Pages;

use App\Filament\Resources\HackathonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHackathons extends ListRecords
{
    protected static string $resource = HackathonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
