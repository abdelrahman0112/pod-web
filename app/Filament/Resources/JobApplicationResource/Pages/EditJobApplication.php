<?php

namespace App\Filament\Resources\JobApplicationResource\Pages;

use App\Filament\Resources\JobApplicationResource;
use App\JobApplicationStatus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobApplication extends EditRecord
{
    protected static string $resource = JobApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('accept')
                ->label('Accept Application')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->accept();
                    \Filament\Notifications\Notification::make()
                        ->title('Application Accepted')
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => in_array($this->record->status, [JobApplicationStatus::PENDING, JobApplicationStatus::REVIEWED])),
            Actions\Action::make('reject')
                ->label('Reject Application')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->reject();
                    \Filament\Notifications\Notification::make()
                        ->title('Application Rejected')
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => $this->record->status !== JobApplicationStatus::REJECTED),
            Actions\DeleteAction::make(),
        ];
    }
}
