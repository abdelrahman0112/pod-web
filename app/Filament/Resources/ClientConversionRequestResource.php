<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientConversionRequestResource\Pages;
use App\Models\ClientConversionRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientConversionRequestResource extends Resource
{
    protected static ?string $model = ClientConversionRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Business Requests';

    protected static ?string $modelLabel = 'Business Account Request';

    protected static ?string $pluralModelLabel = 'Business Account Requests';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['user', 'reviewer']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('user_name')
                            ->label('User Name')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                $component->state($record?->user?->name ?? '-');
                            }),
                        Forms\Components\TextInput::make('user_email')
                            ->label('User Email')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                $component->state($record?->user?->email ?? '-');
                            }),
                        Forms\Components\Hidden::make('user_id')
                            ->required()
                            ->dehydrated(),
                    ]),

                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('business_field')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('company_website')
                            ->url()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('linkedin_company_page')
                            ->url()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Textarea::make('additional_info')
                            ->label('Additional Information')
                            ->rows(5)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Review Information')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending')
                            ->helperText('Changing status to approved will automatically upgrade the user to a client account.'),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes / Rejection Reason')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('This will be visible to the user if the request is rejected.'),
                        Forms\Components\TextInput::make('reviewer_name')
                            ->label('Reviewed By')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                $value = $record?->reviewer?->name;
                                if (! $value && $record?->reviewed_by) {
                                    $value = 'Unknown User';
                                }
                                $component->state($value ?: '-');
                            }),
                        Forms\Components\Hidden::make('reviewed_by')
                            ->dehydrated(),
                        Forms\Components\DateTimePicker::make('reviewed_at')
                            ->disabled()
                            ->dehydrated(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->description(fn (ClientConversionRequest $record): string => $record->user->email),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable()
                    ->description(fn (ClientConversionRequest $record): string => $record->business_field),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-x-circle' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->description(fn (ClientConversionRequest $record): string => $record->created_at->diffForHumans()),
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewed By')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->label('Reviewed At')
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Business Account Request')
                    ->modalDescription(fn (ClientConversionRequest $record) => "Are you sure you want to approve the business account request from {$record->user->name} for {$record->company_name}? The user will be upgraded to a client account.")
                    ->modalSubmitActionLabel('Approve Request')
                    ->action(function (ClientConversionRequest $record) {
                        $record->approve(auth()->user());

                        Notification::make()
                            ->success()
                            ->title('Request Approved')
                            ->body("Business account request from {$record->user->name} has been approved.")
                            ->send();
                    })
                    ->visible(fn (ClientConversionRequest $record) => $record->status === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Rejection Reason')
                            ->placeholder('Provide a reason for rejection (optional, but recommended)')
                            ->rows(3),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Reject Business Account Request')
                    ->modalDescription(fn (ClientConversionRequest $record) => "Are you sure you want to reject the business account request from {$record->user->name}?")
                    ->modalSubmitActionLabel('Reject Request')
                    ->action(function (ClientConversionRequest $record, array $data) {
                        $record->reject(auth()->user(), $data['admin_notes'] ?? null);

                        Notification::make()
                            ->success()
                            ->title('Request Rejected')
                            ->body("Business account request from {$record->user->name} has been rejected.")
                            ->send();
                    })
                    ->visible(fn (ClientConversionRequest $record) => $record->status === 'pending'),
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
            'index' => Pages\ListClientConversionRequests::route('/'),
            'view' => Pages\ViewClientConversionRequest::route('/{record}'),
            'edit' => Pages\EditClientConversionRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();

        return $pendingCount > 0 ? 'warning' : null;
    }
}
