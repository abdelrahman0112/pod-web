<?php

namespace App\Filament\Resources\JobListingResource\Pages;

use App\Filament\Resources\JobListingResource;
use App\JobStatus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobListing extends EditRecord
{
    protected static string $resource = JobListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('close')
                ->label('Close Job Listing')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => JobStatus::CLOSED->value]);
                    \Filament\Notifications\Notification::make()
                        ->title('Job Listing Closed')
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => $this->record->status === JobStatus::ACTIVE->value),
            Actions\Action::make('activate')
                ->label('Activate Job Listing')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => JobStatus::ACTIVE->value]);
                    \Filament\Notifications\Notification::make()
                        ->title('Job Listing Activated')
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => in_array($this->record->status, [JobStatus::CLOSED->value, JobStatus::ARCHIVED->value])),
            Actions\DeleteAction::make(),
        ];
    }
}
