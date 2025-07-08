<?php

namespace App\Filament\Resources\PlikResource\Pages;

use App\Filament\Resources\PlikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlik extends EditRecord
{
    protected static string $resource = PlikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
