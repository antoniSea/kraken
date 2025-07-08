<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlikRelationManager extends RelationManager
{
    protected static string $relationship = 'pliki';
    protected static ?string $title = 'Pliki';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('original_name')->label('Nazwa pliku')->searchable(),
                TextColumn::make('path')->label('Plik')->url(fn($record) => '/storage/' . $record->path, true)->openUrlInNewTab(),
                TextColumn::make('size')->sortable(),
                TextColumn::make('created_at')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
} 