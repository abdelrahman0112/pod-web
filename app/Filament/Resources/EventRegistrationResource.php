<?php

namespace App\Filament\Resources;

use App\EventRegistrationStatus;
use App\Filament\Resources\EventRegistrationResource\Pages;
use App\Models\EventRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Registrations';

    protected static ?string $navigationGroup = 'Events';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registration Information')
                    ->schema([
                        Forms\Components\Select::make('event_id')
                            ->label('Event')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email)
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(EventRegistrationStatus::options())
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Ticket Information')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_code')
                            ->label('Ticket Code')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300']),
                        Forms\Components\TextInput::make('text_code')
                            ->label('Text Code')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300']),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Check-in Information')
                    ->schema([
                        Forms\Components\Toggle::make('checked_in')
                            ->label('Checked In')
                            ->required(),
                        Forms\Components\DateTimePicker::make('checked_in_at')
                            ->label('Checked In At'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
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
                Tables\Columns\TextColumn::make('text_code')
                    ->label('Text Code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('checked_in')
                    ->label('Checked In')
                    ->boolean(),
                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Checked In At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(EventRegistrationStatus::options()),
            ])
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventRegistrations::route('/'),
            'create' => Pages\CreateEventRegistration::route('/create'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }
}
