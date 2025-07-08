<?php

namespace App\Filament\Resources\KonkursResource\Pages;

use App\Filament\Resources\KonkursResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKonkurs extends EditRecord
{
    protected static string $resource = KonkursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
