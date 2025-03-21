<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LicenseActivation extends Page
{
    protected static string $view = 'filament.pages.license-activation';
    protected ?string $heading = '';
    protected static bool $shouldRegisterNavigation = false;

    public function form(Form $form): Form {
        return $form->schema([
            TextInput::make('title')->label('Enter License')->required(),
        ]);
    }
    public function submit(): void
    {
        // Ambil data form
        $data = $this->form->getState();
        dd('Form Submitted', $data);
    }
}
