<?php

namespace App\Filament\Company\Resources\MotorPools;

use App\Filament\Company\Resources\MotorPools\Pages\CreateMotorPool;
use App\Filament\Company\Resources\MotorPools\Pages\EditMotorPool;
use App\Filament\Company\Resources\MotorPools\Pages\ListMotorPools;
use App\Filament\Company\Resources\MotorPools\Schemas\MotorPoolForm;
use App\Filament\Company\Resources\MotorPools\Tables\MotorPoolsTable;
use App\Models\MotorPool;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MotorPoolResource extends Resource
{
    protected static ?string $model = MotorPool::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;
    protected static ?string $navigationLabel = 'Motor Pool';
    protected static ?string $modelLabel      = 'Vehicle';
    protected static ?int    $navigationSort  = 10;

    // فقط برای ارکان نمایش داده بشه
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        $company = $user->company;
        return $company && str_contains(strtolower($company->name), 'arkan') ||
               str_contains($company->name, 'ارکان');
    }

    public static function form(Schema $schema): Schema
    {
        return MotorPoolForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MotorPoolsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMotorPools::route('/'),
            'create' => CreateMotorPool::route('/create'),
            'edit'   => EditMotorPool::route('/{record}/edit'),
        ];
    }
}
