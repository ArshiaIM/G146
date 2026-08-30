<?php

namespace App\Filament\Company\Resources\Warehouses;

use App\Filament\Company\Resources\Warehouses\Pages\CreateWarehouse;
use App\Filament\Company\Resources\Warehouses\Pages\EditWarehouse;
use App\Filament\Company\Resources\Warehouses\Pages\ListWarehouses;
use App\Filament\Company\Resources\Warehouses\Schemas\WarehouseForm;
use App\Filament\Company\Resources\Warehouses\Tables\WarehousesTable;
use App\Models\Warehouse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;
    protected static ?string $navigationLabel = 'Warehouse';
    protected static ?string $modelLabel      = 'Item';
    protected static ?int    $navigationSort  = 9;

    public static function form(Schema $schema): Schema
    {
        return WarehouseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehousesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'edit'   => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
