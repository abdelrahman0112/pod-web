<?php

namespace App\Filament\Resources\ClientConversionRequestResource\Pages;

use App\Filament\Resources\ClientConversionRequestResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewClientConversionRequest extends ViewRecord
{
    protected static string $resource = ClientConversionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve Request')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Business Account Request')
                ->modalDescription(fn () => "Are you sure you want to approve the business account request from {$this->record->user->name} for {$this->record->company_name}? The user will be upgraded to a client account.")
                ->modalSubmitActionLabel('Approve Request')
                ->action(function () {
                    $this->record->approve(auth()->user());

                    Notification::make()
                        ->success()
                        ->title('Request Approved')
                        ->body("Business account request from {$this->record->user->name} has been approved.")
                        ->send();

                    return redirect()->route('filament.admin.resources.client-conversion-requests.index');
                })
                ->visible(fn () => $this->record->status === 'pending'),

            Actions\Action::make('reject')
                ->label('Reject Request')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Forms\Components\Textarea::make('admin_notes')
                        ->label('Rejection Reason')
                        ->placeholder('Provide a reason for rejection (optional, but recommended)')
                        ->rows(4),
                ])
                ->requiresConfirmation()
                ->modalHeading('Reject Business Account Request')
                ->modalDescription(fn () => "Are you sure you want to reject the business account request from {$this->record->user->name}?")
                ->modalSubmitActionLabel('Reject Request')
                ->action(function (array $data) {
                    $this->record->reject(auth()->user(), $data['admin_notes'] ?? null);

                    Notification::make()
                        ->success()
                        ->title('Request Rejected')
                        ->body("Business account request from {$this->record->user->name} has been rejected.")
                        ->send();

                    return redirect()->route('filament.admin.resources.client-conversion-requests.index');
                })
                ->visible(fn () => $this->record->status === 'pending'),
        ];
    }
}
