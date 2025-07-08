<?php

namespace App\Filament\Resources\KonkursResource\Pages;

use App\Filament\Resources\KonkursResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKonkurs extends ListRecords
{
    protected static string $resource = KonkursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
