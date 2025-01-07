<?php

namespace App\Filament\Resources\ManuelPersonelResource\Pages;

use App\Filament\Resources\ManuelPersonelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManuelPersonels extends ListRecords
{
    protected static string $resource = ManuelPersonelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
