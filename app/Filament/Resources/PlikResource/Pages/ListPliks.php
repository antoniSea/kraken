<?php

namespace App\Filament\Resources\PlikResource\Pages;

use App\Filament\Resources\PlikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPliks extends ListRecords
{
    protected static string $resource = PlikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
