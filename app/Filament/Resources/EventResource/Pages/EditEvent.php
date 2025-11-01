<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load creator relationship and set created_by to creator's name for display
        if (isset($data['created_by']) && $this->record) {
            if (! $this->record->relationLoaded('creator')) {
                $this->record->load('creator');
            }
            $data['created_by'] = $this->record->creator?->name ?? 'N/A';
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('activate')
                ->label('Activate Event')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['is_active' => true]);
                    \Filament\Notifications\Notification::make()
                        ->title('Event Activated')
                        ->success()
                        ->send();
                })
                ->visible(fn () => ! $this->record->is_active),

            Actions\Action::make('deactivate')
                ->label('Deactivate Event')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['is_active' => false]);
                    \Filament\Notifications\Notification::make()
                        ->title('Event Deactivated')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->record->is_active),

            Actions\DeleteAction::make(),
        ];
    }
}
