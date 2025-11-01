<?php

namespace App\Filament\Widgets;

use App\EventRegistrationStatus;
use App\Models\EventRegistration;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentEventRegistrations extends BaseWidget
{
    protected static ?string $heading = 'Event Registrations Requiring Action';

    protected int|string|array $columnSpan = [
        'xl' => 12,
    ];

    protected static ?int $sort = 7;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->limit(30),
                Tables\Columns\TextColumn::make('event.start_date')
                    ->label('Event Date')
                    ->dateTime('M d, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->color(fn ($record) => match ($record->status) {
                        EventRegistrationStatus::CONFIRMED => 'success',
                        EventRegistrationStatus::WAITLISTED => 'warning',
                        EventRegistrationStatus::CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'Unknown'),
                Tables\Columns\TextColumn::make('ticket_code')
                    ->label('Ticket Code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('checked_in')
                    ->label('Checked In')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->label('Registered')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->recordUrl(fn ($record) => route('filament.admin.resources.event-registrations.edit', $record))
            ->actions([
                Tables\Actions\Action::make('checkIn')
                    ->label('Check In')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Check In Attendee')
                    ->modalDescription('Are you sure you want to check in this attendee?')
                    ->action(function (EventRegistration $record) {
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

                        \Filament\Notifications\Notification::make()
                            ->title('Checked In Successfully')
                            ->success()
                            ->body('The attendee has been checked in.')
                            ->send();
                    })
                    ->visible(fn (EventRegistration $record) => ! $record->checked_in && $record->status === EventRegistrationStatus::CONFIRMED),
                Tables\Actions\Action::make('edit')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.event-registrations.edit', $record)),
            ]);
    }

    protected function getQuery(): Builder
    {
        return EventRegistration::query()
            ->with(['event', 'user'])
            ->where('status', EventRegistrationStatus::CONFIRMED->value)
            ->where('checked_in', false)
            ->whereHas('event', function ($query) {
                $query->where('start_date', '>=', now()->subDay()); // Events starting from yesterday onwards
            })
            ->latest();
    }
}
