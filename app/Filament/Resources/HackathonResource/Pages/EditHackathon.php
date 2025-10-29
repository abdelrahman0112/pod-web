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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert technologies array to comma-separated string for the form
        if (isset($data['technologies']) && is_array($data['technologies'])) {
            $data['technologies'] = implode(', ', $data['technologies']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Convert technologies from comma-separated string to array
        if (isset($data['technologies']) && is_string($data['technologies'])) {
            $data['technologies'] = array_map('trim', explode(',', $data['technologies']));
        }

        return $data;
    }
}
