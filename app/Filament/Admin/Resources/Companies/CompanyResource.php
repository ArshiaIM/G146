<?php

namespace App\Filament\Admin\Resources\Companies;

use App\Filament\Admin\Resources\Companies\Pages\CreateCompany;
use App\Filament\Admin\Resources\Companies\Pages\EditCompany;
use App\Filament\Admin\Resources\Companies\Pages\ListCompanies;
use App\Models\Battalion;
use App\Models\Company;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'گروهان‌ها';
    protected static ?string $modelLabel      = 'گروهان';
    protected static ?int    $navigationSort  = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('battalion_id')
                ->label('گردان')
                ->options(Battalion::pluck('name', 'id'))
                ->required(),

            TextInput::make('name')
                ->label('نام گروهان')
                ->required(),

            Select::make('commander_id')
                ->label('فرمانده')
                ->options(
                    \App\Models\Personnel::where('rank_type', 'officer_a')
                        ->get()
                        ->mapWithKeys(fn($p) => [
                            $p->id => $p->rank_label . ' ' . $p->full_name
                        ])
                )
                ->searchable()
                ->nullable(),

            TextInput::make('code')
                ->label('کد لاگین')
                ->unique(ignoreRecord: true)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('battalion.name')
                    ->label('گردان')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('گروهان')
                    ->sortable(),

                TextColumn::make('commander')
                    ->label('فرمانده')
                    ->placeholder('—'),

                TextColumn::make('code')
                    ->label('کد لاگین')
                    ->placeholder('—'),

                TextColumn::make('total_personnel')
                    ->label('کل پرسنل')
                    ->getStateUsing(fn($record) => $record->personnel()->count()),

                TextColumn::make('active_personnel')
                    ->label('فعال')
                    ->getStateUsing(fn($record) => $record->personnel()->where('status', 'active')->count())
                    ->color('success'),

                TextColumn::make('readiness')
                    ->label('آمادگی')
                    ->getStateUsing(function ($record) {
                        $total  = $record->personnel()->count();
                        $active = $record->personnel()->where('status', 'active')->count();
                        return $total > 0 ? round(($active / $total) * 100) . '٪' : '0٪';
                    })
                    ->badge()
                    ->color(function ($record) {
                        $total  = $record->personnel()->count();
                        $active = $record->personnel()->where('status', 'active')->count();
                        $pct    = $total > 0 ? round(($active / $total) * 100) : 0;
                        return $pct >= 80 ? 'success' : ($pct >= 60 ? 'warning' : 'danger');
                    }),
            ])
            ->filters([
                SelectFilter::make('battalion_id')
                    ->label('گردان')
                    ->options(Battalion::pluck('name', 'id')),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('ویرایش'),
                \Filament\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('+ گروهان جدید'),
            ])
            ->defaultSort('battalion_id');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit'   => EditCompany::route('/{record}/edit'),
        ];
    }
}
