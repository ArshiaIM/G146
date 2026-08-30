<?php

namespace App\Filament\Company\Resources\Letters\Tables;

use App\Models\Personnel;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->where('company_id', auth()->user()?->company_id)
                    ->with(['personnel', 'company.battalion'])
            )
            ->columns([
                TextColumn::make('personnel.full_name')
                    ->label('نام پرسنل')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('personnel.rank_label')
                    ->label('رتبه'),

                TextColumn::make('type')
                    ->label('نوع')
                    ->formatStateUsing(fn($state) => $state === 'incoming' ? '📥 وارده' : '📤 صادره')
                    ->badge()
                    ->color(fn($state) => $state === 'incoming' ? 'info' : 'primary'),

                TextColumn::make('category')
                    ->label('موضوع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'leave'      => '🏖️ مرخصی',
                        'punishment' => '⚠️ تنبیه',
                        'reward'     => '🌟 تشویق',
                        'medical'    => '🏥 بهداری',
                        'absence'    => '⚠️ غیبت',
                        'general'    => '📋 عمومی',
                        default      => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'punishment', 'absence' => 'danger',
                        'reward'                => 'success',
                        'medical'               => 'warning',
                        'leave'                 => 'info',
                        default                 => 'gray',
                    }),

                TextColumn::make('subject')
                    ->label('موضوع نامه')
                    ->limit(35)
                    ->searchable(),

                TextColumn::make('letter_number')
                    ->label('شماره نامه')
                    ->placeholder('—'),

                TextColumn::make('letter_date')
                    ->label('تاریخ')
                    ->formatStateUsing(
                        fn($state) =>
                        $state
                            ? \Morilog\Jalali\Jalalian::fromCarbon(
                                \Carbon\Carbon::parse($state)
                            )->format('Y/m/d')
                            : '—'
                    )
                    ->sortable(),

                IconColumn::make('attachment')
                    ->label('پیوست')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-minus'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('نوع')
                    ->options([
                        'incoming' => 'وارده',
                        'outgoing' => 'صادره',
                    ]),

                SelectFilter::make('category')
                    ->label('موضوع')
                    ->options([
                        'leave'      => 'مرخصی',
                        'punishment' => 'تنبیه',
                        'reward'     => 'تشویق',
                        'medical'    => 'بهداری',
                        'absence'    => 'غیبت',
                        'general'    => 'عمومی',
                    ]),

                SelectFilter::make('personnel_id')
                    ->label('پرسنل')
                    ->options(
                        fn() =>
                        Personnel::where('company_id', auth()->user()?->company_id)
                            ->get()
                            ->mapWithKeys(fn($p) => [$p->id => $p->full_name])
                    )
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('2xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن')
                    ->modalContent(fn($record) => new \Illuminate\Support\HtmlString('
        <div style="
            direction:rtl;
            font-family: Tahoma, Arial, sans-serif;
            width: 148mm;
            min-height: 210mm;
            margin: 0 auto;
            padding: 12mm 10mm;
            border: 1px solid #ccc;
            background: white;
            font-size: 13px;
            line-height: 2;
            box-shadow: 0 2px 8px rgb(255, 255, 255);
        ">
            <div style="color:#000;text-align:center;font-weight:bold;font-size:15px;margin-bottom:16px;border-bottom:2px solid #333;padding-bottom:8px;">
                به نام خدا
            </div>

            <!-- ردیف اول: از (راست) | شماره (چپ) -->
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <div>
                    <span style="color:#000;">شماره :</span>
                    <span style="border-bottom:1px solid #333;min-width:80px;display:inline-block;padding:0 4px;color:#000;">
                        ' . ($record->letter_number ?? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') . '
                    </span>
                </div>
                <div>
                    <span style="color:#000;">از :</span>
                    <span style="font-weight:bold;color:#000;"> ف.گر ' . ($record->company?->name ?? '') . '</span>
                </div>
            </div>

            <!-- ردیف دوم: به (راست) | تاریخ (چپ) -->
            <div style="text-align:left;display:flex;justify-content:space-between;margin-bottom:6px;">
                <div>
                    <span style="color:#000;">تاریخ :</span>
                    <span style="color:#000;border-bottom:1px solid #333;min-width:80px;display:inline-block;padding:0 4px;">
                        ' . ($record->letter_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($record->letter_date))->format('Y/m/d') : '') . '
                    </span>
                </div>
                <div>
                    <span style="text-align:right;color:#000;">به :</span>
                    <span style="text-align:right;font-weight:bold;color:#000;"> ف.محترم.گد ' . ($record->company?->battalion?->name ?? '') . '</span>
                </div>
            </div>

            <!-- موضوع راست‌چین -->
            <div style="text-align:right;margin-bottom:16px;">
                <span style="color:#000;">موضوع :</span>
                <span style="color:#000;font-weight:bold;margin-right:4px;">' . $record->subject . '</span>
            </div>

            <!-- سلام راست‌چین -->
            <div style="color:#000;text-align:right;margin-bottom:12px;">با درود و مهر</div>

            <!-- متن وسط‌چین -->
            <div style="color:#000;text-align:justify;margin-bottom:24px;">
                ،
                ' . strip_tags($record->content) . '
            </div>

            <!-- امضا -->
            <div style="margin-top:40px;text-align:left;">
                <div style="font-weight:bold;color:#000;">ف.گر ' . ($record->company?->name ?? '') . '</div>
                <div style="margin-top:4px;color:#000;">' . ($record->company?->commander ?? '') . '</div>
            </div>
        </div>
    ')),
                EditAction::make()->label('ویرایش'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف'),
                ]),
            ])
            ->defaultSort('letter_date', 'desc');
    }
}
