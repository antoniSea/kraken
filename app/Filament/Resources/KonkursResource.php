<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KonkursResource\Pages;
use App\Filament\Resources\KonkursResource\RelationManagers;
use App\Models\Konkurs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KonkursResource extends Resource
{
    protected static ?string $model = Konkurs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nazwa konkursu')->required(),
                Forms\Components\Toggle::make('is_blocked')->label('Zablokowany?'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nazwa konkursu')->searchable(),
                Tables\Columns\IconColumn::make('is_blocked')->label('Zablokowany?')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edytuj'),
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
            'index' => Pages\ListKonkurs::route('/'),
            'create' => Pages\CreateKonkurs::route('/create'),
            'edit' => Pages\EditKonkurs::route('/{record}/edit'),
        ];
    }
}
