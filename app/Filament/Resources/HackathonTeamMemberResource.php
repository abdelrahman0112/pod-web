<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HackathonTeamMemberResource\Pages;
use App\Models\HackathonTeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HackathonTeamMemberResource extends Resource
{
    protected static ?string $model = HackathonTeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Team Members';

    protected static ?string $navigationGroup = 'Hackathons';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Team Member Information')
                    ->schema([
                        Forms\Components\Select::make('team_id')
                            ->label('Team')
                            ->relationship('team', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->label('Member')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email)
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('role')
                            ->label('Role')
                            ->maxLength(255)
                            ->helperText('e.g., Developer, Designer, Product Manager'),
                        Forms\Components\TagsInput::make('skills')
                            ->label('Skills')
                            ->placeholder('Add a skill (e.g., React, Python, UI/UX)')
                            ->separator(',')
                            ->splitKeys(['Tab', ',']),
                        Forms\Components\DateTimePicker::make('joined_at')
                            ->label('Joined At')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->with(['team', 'user']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->user->name ?? 'N/A'),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('skills')
                    ->label('Skills')
                    ->badge()
                    ->separator(',')
                    ->limit(3),
                Tables\Columns\TextColumn::make('joined_at')
                    ->label('Joined At')
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
                //
            ])
            ->actions([
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
            'index' => Pages\ListHackathonTeamMembers::route('/'),
            'create' => Pages\CreateHackathonTeamMember::route('/create'),
            'edit' => Pages\EditHackathonTeamMember::route('/{record}/edit'),
        ];
    }
}
