<?php

namespace App\Filament\Company\Resources\Letters\Schemas;

use App\Models\Personnel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Hidden::make('company_id')
                ->dehydrateStateUsing(fn() => auth()->user()?->company_id)
                ->dehydrated(true),

            Section::make('اطلاعات نامه')
                ->schema([
                    Select::make('personnel_id')
                        ->label('پرسنل')
                        ->options(fn() =>
                            Personnel::where('company_id', auth()->user()?->company_id)
                                ->get()
                                ->mapWithKeys(fn($p) => [
                                    $p->id => $p->rank_label . ' ' . $p->full_name
                                ])
                        )
                        ->searchable()
                        ->required(),

                    Select::make('type')
                        ->label('نوع نامه')
                        ->options([
                            'incoming' => '📥 وارده',
                            'outgoing' => '📤 صادره',
                        ])
                        ->required(),

                    Select::make('category')
                        ->label('موضوع کلی')
                        ->options([
                            'leave'      => '🏖️ مرخصی',
                            'punishment' => '⚠️ تنبیه',
                            'reward'     => '🌟 تشویق',
                            'medical'    => '🏥 بهداری',
                            'absence'    => '⚠️ غیبت',
                            'general'    => '📋 عمومی',
                        ])
                        ->default('general')
                        ->required(),
                ])
                ->columns(3),

            Section::make('مشخصات نامه')
                ->schema([
                    TextInput::make('letter_number')
                        ->label('شماره نامه')
                        ->nullable(),

                    DatePicker::make('letter_date')
                        ->label('تاریخ نامه')
                        ->default(today())
                        ->required(),

                    TextInput::make('subject')
                        ->label('موضوع نامه')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('متن نامه')
                ->schema([
                    RichEditor::make('content')
                        ->label('متن')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'bulletList', 'orderedList',
                            'redo', 'undo',
                        ]),

                    FileUpload::make('attachment')
                        ->label('پیوست')
                        ->directory('letters')
                        ->acceptedFileTypes(['application/pdf', 'image/*'])
                        ->nullable(),
                ]),
        ]);
    }
}
