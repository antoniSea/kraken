<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\PlikRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Użytkownicy';
    protected static ?string $modelLabel = 'Użytkownik';
    protected static ?string $pluralModelLabel = 'Użytkownicy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Imię i nazwisko')->required(),
                Forms\Components\TextInput::make('email')->label('E-mail')->email()->required(),
                Forms\Components\TextInput::make('nickname')->label('Nick'),
                Forms\Components\TextInput::make('first_name')->label('Imię'),
                Forms\Components\TextInput::make('last_name')->label('Nazwisko'),
                Forms\Components\TextInput::make('phone')->label('Telefon'),
                Forms\Components\TextInput::make('city')->label('Miasto'),
                Forms\Components\TextInput::make('apartment')->label('Mieszkanie'),
                Forms\Components\Toggle::make('consent_personal_data')->label('Zgoda na przetwarzanie danych osobowych'),
                Forms\Components\Toggle::make('consent_email')->label('Zgoda na kontakt e-mail'),
                Forms\Components\Toggle::make('consent_marketing')->label('Zgoda na marketing'),
                Forms\Components\Toggle::make('is_admin')->label('Administrator'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Imię i nazwisko')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                Tables\Columns\TextColumn::make('nickname')->label('Nick')->searchable(),
                Tables\Columns\IconColumn::make('is_admin')->label('Administrator')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Data utworzenia')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edytuj'),
                Tables\Actions\DeleteAction::make()->label('Usuń'),
                Tables\Actions\Action::make('pliki')
                    ->label('Pliki')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => route('filament.admin.resources.users.edit', ['record' => $record->getKey(), 'activeRelationManager' => 'pliki']))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Usuń zaznaczone'),
                    BulkAction::make('sendMail')
                        ->label('Wyślij maila')
                        ->icon('heroicon-o-envelope')
                        ->form([
                            \Filament\Forms\Components\Textarea::make('message')
                                ->label('Treść maila')
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data) {
                            foreach ($records as $user) {
                                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                                    \Illuminate\Support\Facades\Mail::raw($data['message'], function ($message) use ($user) {
                                        $message->to($user->email)
                                            ->subject('Wiadomość od administratora');
                                    });
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('primary'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PlikRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
