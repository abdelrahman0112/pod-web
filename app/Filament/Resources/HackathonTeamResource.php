<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HackathonTeamResource\Pages;
use App\Models\HackathonTeam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HackathonTeamResource extends Resource
{
    protected static ?string $model = HackathonTeam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Teams';

    protected static ?string $navigationGroup = 'Hackathons';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('hackathon_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('leader_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('project_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('project_description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('project_repository')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_looking_for_members')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hackathon_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leader_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_repository')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_looking_for_members')
                    ->boolean(),
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
            'index' => Pages\ListHackathonTeams::route('/'),
            'create' => Pages\CreateHackathonTeam::route('/create'),
            'edit' => Pages\EditHackathonTeam::route('/{record}/edit'),
        ];
    }
}
