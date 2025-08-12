<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\PlikRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PointsHistoryRelationManager;
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
                Forms\Components\TextInput::make('points')->label('Punkty')->numeric()->default(0),
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
                Tables\Columns\TextColumn::make('points')->label('Punkty')->sortable()->badge()->color('success'),
                Tables\Columns\IconColumn::make('is_admin')->label('Administrator')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Data utworzenia')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('points_range')
                    ->label('Zakres punktów')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('min_points')
                            ->label('Minimalna liczba punktów')
                            ->numeric()
                            ->minValue(0),
                        \Filament\Forms\Components\TextInput::make('max_points')
                            ->label('Maksymalna liczba punktów')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_points'],
                                fn (Builder $query, $minPoints): Builder => $query->where('points', '>=', $minPoints),
                            )
                            ->when(
                                $data['max_points'],
                                fn (Builder $query, $maxPoints): Builder => $query->where('points', '<=', $maxPoints),
                            );
                    }),
            ])
            ->defaultSort('points', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()->label('Edytuj'),
                Tables\Actions\DeleteAction::make()->label('Usuń'),
                Tables\Actions\Action::make('pliki')
                    ->label('Pliki')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => route('filament.admin.resources.users.edit', ['record' => $record->getKey(), 'activeRelationManager' => 'pliki']))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('addPoints')
                    ->label('+ Punkty')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('points')
                            ->label('Liczba punktów do dodania')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        \Filament\Forms\Components\TextInput::make('reason')
                            ->label('Powód (opcjonalnie)')
                            ->placeholder('Np. Za aktywność w konkursie'),
                    ])
                                            ->action(function ($record, array $data) {
                            $record->addPoints($data['points'], $data['reason'] ?? 'Punkty dodane przez administratora');
                        })
                    ->requiresConfirmation(),
                                Tables\Actions\Action::make('subtractPoints')
                    ->label('- Punkty')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('points')
                            ->label('Liczba punktów do odjęcia')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        \Filament\Forms\Components\TextInput::make('reason')
                            ->label('Powód (opcjonalnie)')
                            ->placeholder('Np. Za naruszenie regulaminu'),
                    ])
                                            ->action(function ($record, array $data) {
                            $record->subtractPoints($data['points'], $data['reason'] ?? 'Punkty odjęte przez administratora');
                        })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('pointsHistory')
                    ->label('Historia punktów')
                    ->icon('heroicon-o-clock')
                    ->url(fn($record) => route('filament.admin.resources.users.edit', ['record' => $record->getKey(), 'activeRelationManager' => 'pointsHistory']))
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
                            Forms\Components\FileUpload::make('attachment')
                                ->label('Załącznik')
                                ->maxSize(10240) // 10MB max
                                ->helperText('Maksymalny rozmiar: 10MB'),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data) {
                            foreach ($records as $user) {
                                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                                    \Illuminate\Support\Facades\Mail::raw($data['message'], function ($message) use ($user, $data) {
                                        $message->to($user->email)
                                            ->subject('Wiadomość od administratora');
                                        
                                        // Dodaj załącznik jeśli został wybrany
                                        if (!empty($data['attachment'])) {
                                            $message->attach(storage_path('app/public/' . $data['attachment']));
                                        }
                                    });
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('primary'),
                    BulkAction::make('addPoints')
                        ->label('Dodaj punkty')
                        ->icon('heroicon-o-star')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('points')
                                ->label('Liczba punktów do dodania')
                                ->numeric()
                                ->required()
                                ->minValue(1),
                            \Filament\Forms\Components\TextInput::make('reason')
                                ->label('Powód (opcjonalnie)')
                                ->placeholder('Np. Za aktywność w konkursie'),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data) {
                            foreach ($records as $user) {
                                $user->addPoints($data['points'], $data['reason'] ?? 'Punkty dodane przez administratora (akcja grupowa)');
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('success'),
                    BulkAction::make('subtractPoints')
                        ->label('Odejmij punkty')
                        ->icon('heroicon-o-minus-circle')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('points')
                                ->label('Liczba punktów do odjęcia')
                                ->numeric()
                                ->required()
                                ->minValue(1),
                            \Filament\Forms\Components\TextInput::make('reason')
                                ->label('Powód (opcjonalnie)')
                                ->placeholder('Np. Za naruszenie regulaminu'),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data) {
                            foreach ($records as $user) {
                                $user->subtractPoints($data['points'], $data['reason'] ?? 'Punkty odjęte przez administratora (akcja grupowa)');
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('danger'),
                    BulkAction::make('resetPoints')
                        ->label('Resetuj punkty')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('reason')
                                ->label('Powód resetu (opcjonalnie)')
                                ->placeholder('Np. Reset po konkursie'),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data) {
                            foreach ($records as $user) {
                                $user->resetPoints($data['reason'] ?? 'Reset punktów przez administratora (akcja grupowa)');
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->color('warning'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PlikRelationManager::class,
            PointsHistoryRelationManager::class,
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
