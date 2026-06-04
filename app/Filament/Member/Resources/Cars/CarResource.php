<?php

namespace App\Filament\Member\Resources\Cars;

use App\Filament\Member\Resources\Cars\Pages\CreateCar;
use App\Filament\Member\Resources\Cars\Pages\EditCar;
use App\Filament\Member\Resources\Cars\Pages\ListCars;
use App\Filament\Member\Resources\Cars\Schemas\CarForm;
use App\Filament\Member\Resources\Cars\Tables\CarsTable;
use App\Models\Car;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationLabel = 'Xe của tôi';
    protected static ?string $pluralModelLabel = 'Danh sách xe';
    protected static ?string $modelLabel = 'Xe của tôi';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static string|\UnitEnum|null $navigationGroup = 'Hoạt động';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCars::route('/'),
            'create' => CreateCar::route('/create'),
            'edit' => EditCar::route('/{record}/edit'),
        ];
    }
}
