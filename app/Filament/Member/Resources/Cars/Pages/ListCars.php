<?php

namespace App\Filament\Member\Resources\Cars\Pages;

use App\Filament\Member\Resources\Cars\CarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCars extends ListRecords
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
