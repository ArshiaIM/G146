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
        'company_id', 'personnel_type', 'rank',
        'first_name', 'last_name', 'national_code',
        'personnel_number', 'phone', 'city',
        'service_start_date', 'service_end_date',
        'status', 'notes',
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

    public function getRankLabelAttribute(): string
    {
        return match ($this->rank) {
            'officer_a'      => 'افسر الف',
            'officer_b'      => 'افسر ب',
            'nco'            => 'درجه‌دار کادر',
            'vazife_officer' => 'افسر وظیفه',
            'vazife_nco'     => 'درجه‌دار وظیفه',
            'soldier'        => 'سرباز',
            default          => $this->rank,
        };
    }

    public function getPersonnelTypeLabelAttribute(): string
    {
        return $this->personnel_type === 'kadre' ? 'کادر' : 'وظیفه';
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

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

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
}
