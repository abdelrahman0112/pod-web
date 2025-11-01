<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Events';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category_id')
                            ->label('Event Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Forms\Components\Section::make('Date & Time')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Start Date & Time')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('End Date & Time'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\Textarea::make('location')
                            ->label('Location')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull()
                            ->helperText('Enter event location (e.g., Cairo, Egypt or Online)'),
                        Forms\Components\Select::make('format')
                            ->label('Event Format')
                            ->options([
                                'online' => 'Online',
                                'in-person' => 'In-Person',
                                'hybrid' => 'Hybrid',
                            ])
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Event Banner')
                    ->schema([
                        Forms\Components\FileUpload::make('banner_image')
                            ->label('Banner Image')
                            ->image()
                            ->directory('events/banners')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->columnSpanFull()
                            ->helperText('Upload a banner image for your event. Max size: 2MB. Supported formats: JPEG, PNG, WebP.'),
                    ]),

                Forms\Components\Section::make('Capacity & Registration')
                    ->schema([
                        Forms\Components\TextInput::make('max_attendees')
                            ->label('Maximum Attendees')
                            ->numeric()
                            ->helperText('Leave empty for unlimited'),
                        Forms\Components\DateTimePicker::make('registration_deadline')
                            ->label('Registration Deadline')
                            ->required(),
                        Forms\Components\Toggle::make('waitlist_enabled')
                            ->label('Enable waitlist when event is full')
                            ->required()
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Event Agenda')
                    ->schema([
                        Forms\Components\Repeater::make('agendaItems')
                            ->label('Agenda Items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Item Title/Description')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DateTimePicker::make('start_time')
                                    ->label('Date & Time')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('M d, Y g:i A')
                                    ->seconds(false),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->helperText('Add agenda items with title and date/time.'),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Admin Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Status')
                            ->required()
                            ->default(true),
                        Forms\Components\TextInput::make('created_by')
                            ->label('Created By')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record !== null)
                            ->helperText('Event creator cannot be changed.'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->with(['category', 'creator']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->category) {
                            return '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">N/A</span>';
                        }

                        $color = $record->category->color ?? '#6B7280';

                        // Simple contrast: use white text for dark backgrounds, black for light
                        $textColor = '#FFFFFF'; // Default to white, works well for most colors

                        return sprintf(
                            '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full" style="background-color: %s; color: %s;">%s</span>',
                            $color,
                            $textColor,
                            e($state ?? 'N/A')
                        );
                    }),
                Tables\Columns\TextColumn::make('format')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'in-person' => 'warning',
                        'hybrid' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_attendees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('waitlist_enabled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => $record->creator ? route('profile.show.other', $record->creator->id) : null)
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn ($state, $record) => $record->creator->name ?? 'N/A'),
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
                //
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Event $record) {
                        $record->update(['is_active' => true]);
                        \Filament\Notifications\Notification::make()
                            ->title('Event Activated')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Event $record) => ! $record->is_active),

                Tables\Actions\Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Event $record) {
                        $record->update(['is_active' => false]);
                        \Filament\Notifications\Notification::make()
                            ->title('Event Deactivated')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Event $record) => $record->is_active),

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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    /**
     * Determine whether the user can view any models.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /**
     * Determine whether the user can create models.
     */
    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isClient());
    }
}
