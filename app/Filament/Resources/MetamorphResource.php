<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetamorphResource\Pages;
use App\Filament\Resources\MetamorphResource\RelationManagers;
use App\Jobs\ConvertMetamorph;
use App\Jobs\ProcessMatemorphJob;
use App\Models\Metamorph;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetamorphResource extends Resource
{
    protected static ?string $model = Metamorph::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('count')->required(),
                    ])
                ])->columnSpan(['lg' => 2]),
                Group::make()->schema([
                    Section::make()->schema([
                        FileUpload::make('attachment')->required()->disk('public')->directory('media')
                        ->dehydrateStateUsing(function ($state, $get) {
                            dispatch(new ProcessMatemorphJob(reset($state), $get('title'), $get('count')));
                            return reset($state);
                        }),
                    ])
                ])->columnSpan(['lg' => 1])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMetamorphs::route('/'),
            'create' => Pages\CreateMetamorph::route('/create'),
            'edit' => Pages\EditMetamorph::route('/{record}/edit'),
        ];
    }
}
