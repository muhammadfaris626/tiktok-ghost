<?php

namespace App\Filament\Resources\MetamorphResource\Pages;

use App\Filament\Resources\MetamorphResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMetamorph extends CreateRecord
{
    protected static string $resource = MetamorphResource::class;

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
}
