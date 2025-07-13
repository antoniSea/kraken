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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlikiUploaded;
use Filament\Notifications\Notification;

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
                TextColumn::make('user.name')
                    ->label('Użytkownik')
                    ->sortable()
                    ->searchable()
                    ->url(fn($record) => route('filament.admin.resources.users.edit', $record->user_id))
                    ->openUrlInNewTab(),
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
                Action::make('addPoints')
                    ->label('Dodaj punkty')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('points')
                            ->label('Liczba punktów')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\Textarea::make('reason')
                            ->label('Powód')
                            ->required()
                            ->default('Punkty dodane przez administratora'),
                    ])
                    ->action(function (Plik $record, array $data): void {
                        $record->user->addPoints($data['points'], $data['reason']);
                        
                        Notification::make()
                            ->title('Punkty dodane')
                            ->body("Dodano {$data['points']} punktów użytkownikowi {$record->user->name}")
                            ->success()
                            ->send();
                    }),
                Action::make('subtractPoints')
                    ->label('Odejmij punkty')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\TextInput::make('points')
                            ->label('Liczba punktów')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\Textarea::make('reason')
                            ->label('Powód')
                            ->required()
                            ->default('Punkty odjęte przez administratora'),
                    ])
                    ->action(function (Plik $record, array $data): void {
                        $record->user->subtractPoints($data['points'], $data['reason']);
                        
                        Notification::make()
                            ->title('Punkty odjęte')
                            ->body("Odjęto {$data['points']} punktów użytkownikowi {$record->user->name}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Usuń zaznaczone'),
                    BulkAction::make('sendEmail')
                        ->label('Wyślij mail')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->form([
                            Forms\Components\TextInput::make('subject')
                                ->label('Temat')
                                ->required()
                                ->default('Dziękujemy za wysłanie plików'),
                            Forms\Components\Textarea::make('message')
                                ->label('Wiadomość')
                                ->required()
                                ->default('Dziękujemy za przesłanie plików do konkursu.'),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            // Grupuj pliki według użytkowników
                            $userFiles = $records->groupBy('user_id');
                            $sentCount = 0;
                            $errorCount = 0;
                            
                            foreach ($userFiles as $userId => $files) {
                                $user = \App\Models\User::find($userId);
                                if ($user && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                                    try {
                                        Mail::to($user->email)->send(new PlikiUploaded($user, $files, $data['subject'], $data['message']));
                                        $sentCount++;
                                    } catch (\Exception $e) {
                                        $errorCount++;
                                    }
                                } else {
                                    $errorCount++;
                                }
                            }
                            
                            $message = "Wysłano maile do {$sentCount} użytkowników";
                            if ($errorCount > 0) {
                                $message .= " ({$errorCount} błędów - niepoprawne adresy email)";
                            }
                            
                            Notification::make()
                                ->title('Maile wysłane')
                                ->body($message)
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('addPoints')
                        ->label('Dodaj punkty')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('points')
                                ->label('Liczba punktów')
                                ->numeric()
                                ->required()
                                ->minValue(1),
                            Forms\Components\Textarea::make('reason')
                                ->label('Powód')
                                ->required()
                                ->default('Punkty dodane przez administratora'),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            // Grupuj pliki według użytkowników i dodaj punkty tylko raz na użytkownika
                            $userFiles = $records->groupBy('user_id');
                            
                            foreach ($userFiles as $userId => $files) {
                                $user = \App\Models\User::find($userId);
                                if ($user) {
                                    $user->addPoints($data['points'], $data['reason']);
                                }
                            }
                            
                            Notification::make()
                                ->title('Punkty dodane')
                                ->body("Dodano {$data['points']} punktów do {$userFiles->count()} użytkowników")
                                ->success()
                                ->send();
                        }),
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
