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
                TextColumn::make('user.name')->label('Użytkownik')->sortable()->searchable(),
                TextColumn::make('konkurs.name')->label('Konkurs')->sortable()->searchable(),
                // Zamiast nazwy pliku wyświetlam miniaturę jeśli to obrazek, w przeciwnym razie nazwę
                \Filament\Tables\Columns\TextColumn::make('original_name')
                    ->label('Podgląd / Nazwa')
                    ->formatStateUsing(function ($state, $record) {
                        $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));
                        $imgExts = ['jpg','jpeg','png','gif','webp'];
                        if (in_array($ext, $imgExts)) {
                            $url = '/storage/' . $record->path;
                            return '<img src="' . $url . '" alt="miniatura" style="max-width:48px;max-height:48px;border-radius:6px;border:1px solid #333;object-fit:cover;" />';
                        }
                        return $state;
                    })
                    ->html(),
                TextColumn::make('path')
                    ->label('Plik')
                    ->url(fn($record) => '/storage/' . $record->path, true)
                    ->openUrlInNewTab(),
                TextColumn::make('size')->label('Rozmiar (B)')->sortable(),
                TextColumn::make('created_at')->label('Data dodania')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('konkurs_id')
                    ->label('Konkurs')
                    ->options(fn() => \App\Models\Konkurs::orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable(),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Użytkownik')
                    ->options(fn() => \App\Models\User::orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable(),
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
