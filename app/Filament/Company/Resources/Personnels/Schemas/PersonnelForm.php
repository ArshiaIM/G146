<?php

namespace App\Filament\Company\Resources\Personnels\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonnelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn() => auth()->user()?->company_id),

                Section::make('اطلاعات هویتی')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('نام')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('last_name')
                            ->label('نام خانوادگی')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('national_code')
                            ->label('کد ملی')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->minLength(10),

                        TextInput::make('personnel_number')
                            ->label('شماره پرسنلی')
                            ->unique(ignoreRecord: true)
                            ->nullable(),

                        TextInput::make('phone')
                            ->label('تلفن')
                            ->nullable(),

                        TextInput::make('city')
                            ->label('شهر')
                            ->nullable(),
                    ])
                    ->columns(3),

                Select::make('personnel_type')
                    ->label('رتبه')
                    ->options([
                        'officer_a'      => 'افسر الف',
                        'officer_b'      => 'افسر ب',
                        'nco'            => 'درجه‌دار کادر',
                        'vazife_officer' => 'افسر وظیفه',
                        'vazife_nco'     => 'درجه‌دار وظیفه',
                        'soldier'        => 'سرباز',
                    ])
                    ->required(),

                Select::make('rank')
                    ->label('درجه')
                    ->options([
                        'private'                 => 'سرباز',
                        'corporal'                => 'سرجوخه',
                        'sergeant'                => 'گروهبان سوم',
                        'staff_sergeant'          => 'گروهبان دوم',
                        'sergeant_first_class'    => 'گروهبان یکم',
                        'sergeant_major'          => 'استوار دوم',
                        'command_sergeant_major'  => 'استوار یکم',
                        'third_lieutenant'        => 'ستوان سوم',
                        'second_lieutenant'       => 'ستوان دوم',
                        'first_lieutenant'        => 'ستوان یکم',
                        'captain'                 => 'سروان',
                        'major'                   => 'سرگرد',
                        'lieutenant_colonel'      => 'سرهنگ دوم',
                        'colonel'                 => 'سرهنگ',
                        'second_brigadier_general' => 'سرتیپ دوم',
                        'brigadier_general'       => 'سرتیپ',
                        'major_general'           => 'سرلشکر',
                        'lieutenant_general'      => 'سپهبد',
                        'general'                 => 'ارتشبد',
                    ])
                    ->required(),

                Section::make('تاریخ خدمت')
                    ->schema([
                        DatePicker::make('service_start_date')
                            ->label('تاریخ استخدام')
                            ->nullable(),

                        // DatePicker::make('service_end_date')
                        //     ->label('تاریخ پایان خدمت')
                        //     ->nullable(),
                    ])
                    ->columns(2),

                Section::make('یادداشت')
                    ->schema([
                        Textarea::make('notes')
                            ->label('یادداشت')
                            ->rows(3)
                            ->nullable(),
                    ])
                    ->collapsed(),
            ]);
    }
}
