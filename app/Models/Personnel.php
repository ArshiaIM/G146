<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class Personnel extends Model
{
    use SoftDeletes;

    protected $table = 'personnel';

    protected $fillable = [
        'company_id',
        'personnel_type',
        'rank_type',
        'rank',
        'first_name',
        'last_name',
        'national_code',
        'personnel_number',
        'phone',
        'city',
        'service_start_date',
        'service_end_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'service_start_date' => 'date',
            'service_end_date'   => 'date',
        ];
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getRankTypeLabelAttribute(): string
    {
        return match ($this->rank_type) {
            'officer_a'      => 'افسر الف',
            'officer_b'      => 'افسر ب',
            'nco'            => 'درجه‌دار کادر',
            'vazife_officer' => 'افسر وظیفه',
            'vazife_nco'     => 'درجه‌دار وظیفه',
            'soldier'        => 'سرباز',
            default          => $this->rank_type,
        };
    }

    public function getRankLableAttribute(): string
    {
        return match ($this->rank) {

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
            default                   => $this->rank,
        };
    }

    public function getPersonnelTypeLabelAttribute(): string
    {
        return $this->personnel_type === 'Career' ? 'پایور' : 'وظیفه';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'فعال',
            'leave'    => 'مرخصی',
            'medical'  => 'بهداری',
            'absent'   => 'غیبت',
            'escaped'  => 'فرار',
            'mission'  => 'مأموریت',
            'released' => 'ترخیص شده',
            default    => $this->status,
        };
    }

    // تاریخ شمسی
    public function getServiceStartJalaliAttribute(): ?string
    {
        return $this->service_start_date
            ? Jalalian::fromCarbon($this->service_start_date)->format('Y/m/d')
            : null;
    }

    public function getServiceEndJalaliAttribute(): ?string
    {
        return $this->service_end_date
            ? Jalalian::fromCarbon($this->service_end_date)->format('Y/m/d')
            : null;
    }

    // روزهای باقیمانده خدمت
    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->service_end_date) return null;
        return max(0, now()->diffInDays($this->service_end_date, false));
    }

    // ── Relations ──────────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // public function attendances(): HasMany
    // {
    //     return $this->hasMany(Attendance::class);
    // }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeKadre($query)
    {
        return $query->where('personnel_type', 'kadre');
    }

    public function scopeVazife($query)
    {
        return $query->where('personnel_type', 'vazife');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public static function ranksByType(string $type): array
    {
        return match ($type) {

            'soldier' => [
                'private',
            ],

            'nco', 'vazife_nco' => [
                'corporal',
                'sergeant',
                'staff_sergeant',
                'sergeant_first_class',
                'sergeant_major',
                'command_sergeant_major',
            ],

            'officer_a', 'officer_b' => [
                'third_lieutenant',
                'second_lieutenant',
                'first_lieutenant',
                'captain',
                'major',
                'lieutenant_colonel',
                'colonel',
                'second_brigadier_general',
                'brigadier_general',
                'major_general',
                'lieutenant_general',
                'general',
            ],

            'vazife_officer' => [
                'third_lieutenant',
                'second_lieutenant',
                'first_lieutenant',
                'captain',
            ],

            default => [],
        };
    }
}
