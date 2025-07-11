<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlikResource\Pages;
use App\Filament\Resources\PlikResource\RelationManagers;
use App\Models\Plik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\FileColumn;

class PlikResource extends Resource
{
    protected static ?string $model = Plik::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pliki';
    protected static ?string $modelLabel = 'Plik';
    protected static ?string $pluralModelLabel = 'Pliki';

    public static function form(Form $form): Form
    {
        // Wyłączamy formularz, bo nie można dodawać/edytować pliku w admince
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('user_id')->label('Użytkownik')->sortable(),
                TextColumn::make('konkurs_id')->label('Konkurs')->sortable(),
                TextColumn::make('original_name')->label('Oryginalna nazwa')->searchable(),
                TextColumn::make('path')
                    ->label('Plik')
                    ->url(fn($record) => '/storage/' . $record->path, true)
                    ->openUrlInNewTab(),
                TextColumn::make('size')->label('Rozmiar (B)')->sortable(),
                TextColumn::make('created_at')->label('Data dodania')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->label('Usuń'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Usuń zaznaczone'),
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
            'index' => Pages\ListPliks::route('/'),
        ];
    }
}
