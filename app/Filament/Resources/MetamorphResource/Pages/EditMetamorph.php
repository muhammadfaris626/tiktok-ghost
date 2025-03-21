<?php

namespace App\Filament\Resources\MetamorphResource\Pages;

use App\Filament\Resources\MetamorphResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetamorph extends EditRecord
{
    protected static string $resource = MetamorphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
}
