<?php

namespace App\Filament\Resources\HackathonResource\Pages;

use App\Filament\Resources\HackathonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHackathon extends CreateRecord
{
    protected static string $resource = HackathonResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the created_by field to the authenticated user
        $data['created_by'] = auth()->id();

        // Convert technologies from comma-separated string to array
        if (isset($data['technologies']) && is_string($data['technologies']) && ! empty($data['technologies'])) {
            $data['technologies'] = array_filter(array_map('trim', explode(',', $data['technologies'])));
        } elseif (empty($data['technologies'])) {
            $data['technologies'] = null;
        }

        return $data;
    }
}
