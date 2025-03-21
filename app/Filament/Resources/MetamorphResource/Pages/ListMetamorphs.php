<?php

namespace App\Filament\Resources\MetamorphResource\Pages;

use App\Filament\Resources\MetamorphResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetamorphs extends ListRecords
{
    protected static string $resource = MetamorphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
