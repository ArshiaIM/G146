<?php

namespace App\Filament\Company\Resources\Leaves;

use App\Filament\Company\Resources\Leaves\Pages\CreateLeave;
use App\Filament\Company\Resources\Leaves\Pages\EditLeave;
use App\Filament\Company\Resources\Leaves\Pages\ListLeaves;
use App\Filament\Company\Resources\Leaves\Schemas\LeaveForm;
use App\Filament\Company\Resources\Leaves\Tables\LeavesTable;
use App\Models\Leave;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LeaveRecource';

    public static function form(Schema $schema): Schema
    {
        return LeaveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeavesTable::configure($table);
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
            'index' => ListLeaves::route('/'),
            'create' => CreateLeave::route('/create'),
            'edit' => EditLeave::route('/{record}/edit'),
        ];
    }
}
