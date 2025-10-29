<?php

namespace App\Filament\Resources\ClientConversionRequestResource\Pages;

use App\Filament\Resources\ClientConversionRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListClientConversionRequests extends ListRecords
{
    protected static string $resource = ClientConversionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Requests are created by users from the frontend
        ];
    }
}
