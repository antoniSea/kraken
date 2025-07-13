<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PointsHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'pointsHistory';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('points_change')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('points_change')
                    ->label('Zmiana punktów')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('points_before')
                    ->label('Punkty przed'),
                Tables\Columns\TextColumn::make('points_after')
                    ->label('Punkty po'),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Powód')
                    ->wrap(),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Administrator')
                    ->default('System'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('Y-m-d H:i:s'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
} 