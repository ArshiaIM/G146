<?php

namespace App\Filament\Company\Resources\Armories;

use App\Filament\Company\Resources\Armories\Pages\CreateArmory;
use App\Filament\Company\Resources\Armories\Pages\EditArmory;
use App\Filament\Company\Resources\Armories\Pages\ListArmories;
use App\Filament\Company\Resources\Armories\Schemas\ArmoryForm;
use App\Filament\Company\Resources\Armories\Tables\ArmoriesTable;
use App\Models\Armory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArmoryResource extends Resource
{
    protected static ?string $model = Armory::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;
    // protected static ?string $navigationLabel = 'اسلحه‌خانه';
    // protected static ?string $modelLabel      = 'سلاح';
    // protected static ?int    $navigationSort  = 8;

    public static function form(Schema $schema): Schema
    {
        return ArmoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArmoriesTable::configure($table);
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
            'index' => ListArmories::route('/'),
            'create' => CreateArmory::route('/create'),
            'edit' => EditArmory::route('/{record}/edit'),
        ];
    }
}
