<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternshipResource\Pages;
use App\Models\Internship;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InternshipResource extends Resource
{
    protected static ?string $model = Internship::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Internships';

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
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Company Details')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Company Name')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Internship Details')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options([
                                'full_time' => 'Full Time',
                                'part_time' => 'Part Time',
                                'contract' => 'Contract',
                                'remote' => 'Remote',
                                'hybrid' => 'Hybrid',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->label('Duration')
                            ->maxLength(255)
                            ->helperText('e.g., 3 months, 6 months, 1 year'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dates & Status')
                    ->schema([
                        Forms\Components\DatePicker::make('application_deadline')
                            ->label('Application Deadline')
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'open' => 'Open',
                                'closed' => 'Closed',
                            ])
                            ->required()
                            ->default('draft'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('application_deadline')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('close')
                    ->label('Close')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Internship $record) {
                        $record->update(['status' => 'closed']);
                        \Filament\Notifications\Notification::make()
                            ->title('Internship Closed')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Internship $record) => $record->status === 'open'),

                Tables\Actions\Action::make('reopen')
                    ->label('Re-open')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Internship $record) {
                        $record->update(['status' => 'open']);
                        \Filament\Notifications\Notification::make()
                            ->title('Internship Re-opened')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Internship $record) => $record->status === 'closed'),

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
            'index' => Pages\ListInternships::route('/'),
            'create' => Pages\CreateInternship::route('/create'),
            'edit' => Pages\EditInternship::route('/{record}/edit'),
        ];
    }
}
