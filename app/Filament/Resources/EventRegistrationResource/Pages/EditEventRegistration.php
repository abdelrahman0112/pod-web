<?php

namespace App\Filament\Resources\EventRegistrationResource\Pages;

use App\EventRegistrationStatus;
use App\Filament\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventRegistration extends EditRecord
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('checkIn')
                ->label('Check In')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Check In Attendee')
                ->modalDescription('Are you sure you want to check in this attendee?')
                ->action(function () {
                    $record = $this->record;

                    if ($record->checked_in) {
                        \Filament\Notifications\Notification::make()
                            ->title('Already Checked In')
                            ->warning()
                            ->body('This attendee has already been checked in.')
                            ->send();

                        return;
                    }

                    if ($record->status !== EventRegistrationStatus::CONFIRMED) {
                        \Filament\Notifications\Notification::make()
                            ->title('Cannot Check In')
                            ->warning()
                            ->body('Only confirmed attendees can be checked in.')
                            ->send();

                        return;
                    }

                    $record->checkIn();
                    $this->record->refresh();
                    $this->fillForm();

                    \Filament\Notifications\Notification::make()
                        ->title('Checked In Successfully')
                        ->success()
                        ->body('The attendee has been checked in.')
                        ->send();
                })
                ->visible(fn () => ! $this->record->checked_in && $this->record->status === EventRegistrationStatus::CONFIRMED),
            Actions\DeleteAction::make(),
        ];
    }
}
