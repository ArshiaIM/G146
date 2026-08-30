<?php

namespace App\Filament\Company\Resources\Attendances;

use App\Filament\Company\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Company\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Company\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Company\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Company\Resources\Attendances\Tables\AttendancesTable;
use App\Filament\Company\Widgets\AttendanceStatsWidget;
use App\Models\Attendance;
use App\Models\Personnel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Personnel::class;

    // protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Attendance';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'AttendanceResource';


    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
            \App\Filament\Company\Widgets\AttendanceStatsWidget::class,
        ];
    }
}
