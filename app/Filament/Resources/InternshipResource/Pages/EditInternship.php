<?php

namespace App\Filament\Resources\InternshipResource\Pages;

use App\Filament\Resources\InternshipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternship extends EditRecord
{
    protected static string $resource = InternshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('close')
                ->label('Close Internship')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'closed']);
                    \Filament\Notifications\Notification::make()
                        ->title('Internship Closed')
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => $this->record->status === 'open'),

            Actions\Action::make('reopen')
                ->label('Re-open Internship')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'open']);
                    \Filament\Notifications\Notification::make()
                        ->title('Internship Re-opened')
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => $this->record->status === 'closed'),

            Actions\DeleteAction::make(),
        ];
    }
}
