<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HackathonResource\Pages;
use App\HackathonFormat;
use App\Models\Hackathon;
use App\SkillLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HackathonResource extends Resource
{
    protected static ?string $model = Hackathon::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Hackathons';

    protected static ?string $modelLabel = 'Hackathon';

    protected static ?string $pluralModelLabel = 'Hackathons';

    protected static ?string $navigationGroup = 'Hackathons';

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
                            ->rows(3),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->after('start_date'),
                        Forms\Components\DateTimePicker::make('registration_deadline')
                            ->required()
                            ->before('start_date'),
                    ])->columns(3),

                Forms\Components\Section::make('Team & Participation')
                    ->schema([
                        Forms\Components\TextInput::make('min_team_size')
                            ->numeric()
                            ->required()
                            ->default(2)
                            ->minValue(1),
                        Forms\Components\TextInput::make('max_team_size')
                            ->numeric()
                            ->required()
                            ->default(6)
                            ->minValue(1),
                        Forms\Components\TextInput::make('max_participants')
                            ->numeric()
                            ->minValue(1),
                    ])->columns(3),

                Forms\Components\Section::make('Prizes & Rewards')
                    ->schema([
                        Forms\Components\TextInput::make('prize_pool')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0),
                        Forms\Components\TextInput::make('entry_fee')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Technical Details')
                    ->schema([
                        Forms\Components\Select::make('format')
                            ->options(collect(HackathonFormat::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]))
                            ->required(),
                        Forms\Components\Select::make('skill_requirements')
                            ->options(collect(SkillLevel::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]))
                            ->required(),
                        Forms\Components\TextInput::make('technologies')
                            ->placeholder('e.g. Python, React, AI/ML')
                            ->helperText('Separate technologies with commas'),
                        Forms\Components\Textarea::make('rules')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Details')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Cover Image')
                            ->image()
                            ->directory('hackathons')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('sponsor_id')
                            ->label('Sponsor')
                            ->relationship('sponsor', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Organizer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('format')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state): string => match ($state->value) {
                        'online' => 'success',
                        'on-site' => 'warning',
                        'hybrid' => 'info',
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prize_pool')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('format')
                    ->options(collect(HackathonFormat::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListHackathons::route('/'),
            'create' => Pages\CreateHackathon::route('/create'),
            'edit' => Pages\EditHackathon::route('/{record}/edit'),
        ];
    }
}
