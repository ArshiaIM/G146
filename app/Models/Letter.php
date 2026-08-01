<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Morilog\Jalali\Jalalian;

class Letter extends Model
{
    protected $fillable = [
        'personnel_id', 'company_id', 'subject', 'content',
        'letter_number', 'letter_date', 'type', 'category', 'attachment',
    ];

    protected function casts(): array
    {
        return [
            'letter_date' => 'date',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'incoming' ? 'وارده' : 'صادره';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'leave'       => 'مرخصی',
            'punishment'  => 'تنبیه',
            'reward'      => 'تشویق',
            'medical'     => 'بهداری',
            'absence'     => 'غیبت',
            'general'     => 'عمومی',
            default       => $this->category,
        };
    }

    public function getLetterDateJalaliAttribute(): string
    {
        return Jalalian::fromCarbon($this->letter_date)->format('Y/m/d');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
