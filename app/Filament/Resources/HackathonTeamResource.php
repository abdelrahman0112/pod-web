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
                Forms\Components\Section::make('Team Information')
                    ->schema([
                        Forms\Components\Select::make('hackathon_id')
                            ->label('Hackathon')
                            ->relationship('hackathon', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->label('Team Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('leader_id')
                            ->label('Team Leader')
                            ->relationship('leader', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email)
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Project Details')
                    ->schema([
                        Forms\Components\TextInput::make('project_name')
                            ->label('Project Name')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('project_description')
                            ->label('Project Description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('project_repository')
                            ->label('Project Repository URL')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_public')
                            ->label('Is Looking for Members')
                            ->helperText('Whether the team is open to join requests from others')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->with(['hackathon', 'leader']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('hackathon.title')
                    ->label('Hackathon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Team Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leader.name')
                    ->label('Leader')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->leader->name ?? 'N/A'),
                Tables\Columns\TextColumn::make('project_name')
                    ->label('Project Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_repository')
                    ->label('Repository')
                    ->searchable()
                    ->url(fn ($record) => $record->project_repository)
                    ->openUrlInNewTab()
                    ->limit(30),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Looking for Members')
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
