<?php

namespace App\Filament\Resources\ClientConversionRequestResource\Pages;

use App\Filament\Resources\ClientConversionRequestResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditClientConversionRequest extends EditRecord
{
    protected static string $resource = ClientConversionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected $originalStatus;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->originalStatus = $this->record->status;

        // Ensure relationships are loaded
        if (! $this->record->relationLoaded('user')) {
            $this->record->load('user');
        }
        if (! $this->record->relationLoaded('reviewer')) {
            $this->record->load('reviewer');
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $newStatus = $data['status'] ?? $this->record->status;
        $statusChanged = $newStatus !== $this->originalStatus;

        // If status is not pending, ensure reviewed_by and reviewed_at are set
        if ($newStatus !== 'pending') {
            if (empty($data['reviewed_by'])) {
                $data['reviewed_by'] = auth()->id();
            }
            if (empty($data['reviewed_at'])) {
                $data['reviewed_at'] = now();
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $newStatus = $this->data['status'] ?? $this->record->status;
        $statusChanged = $newStatus !== $this->originalStatus;

        // If status changed to approved, upgrade the user
        if ($statusChanged && $newStatus === 'approved' && $this->originalStatus !== 'approved') {
            if ($this->record->user->role !== 'client') {
                $this->record->user->update(['role' => 'client']);

                Notification::make()
                    ->success()
                    ->title('Request Approved')
                    ->body("Business account request from {$this->record->user->name} has been approved. The user has been upgraded to a client account.")
                    ->send();
            }
        }

        // If status changed to rejected, notify
        if ($statusChanged && $newStatus === 'rejected' && $this->originalStatus !== 'rejected') {
            Notification::make()
                ->success()
                ->title('Request Rejected')
                ->body("Business account request from {$this->record->user->name} has been rejected.")
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
