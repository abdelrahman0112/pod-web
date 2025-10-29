<?php

namespace App\Filament\Resources\HackathonCategoryResource\Pages;

use App\Filament\Resources\HackathonCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHackathonCategory extends EditRecord
{
    protected static string $resource = HackathonCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
