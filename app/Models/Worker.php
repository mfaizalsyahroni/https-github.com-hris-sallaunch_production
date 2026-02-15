<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Worker extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'employee_id',
        'fullname',
        'password',

        'role',              // legacy / manual HRD

        'working_period_start',
        'working_period_end',
        'employment_type',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'working_period_start' => 'date',
        'working_period_end' => 'date',
    ];

    protected $primaryKey = 'employee_id';
    public $incrementing = false;
    protected $keyType = 'int';

    // Route model binding used employee_id
    public function getRouteKeyName()
    {
        return 'employee_id';
    }


    // 🔒 Mutator otomatis untuk hash password
    public function setPasswordAttribute($value)
    {
        // Cek dulu: kalau password sudah di-hash (diawali dengan $2y$), jangan di-hash ulang
        if (!str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class, 'employee_id', 'employee_id');
    }

    public function surveySubmissions()
    {
        return $this->hasMany(SurveySubmission::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class, 'employee_id', 'employee_id');
    }

    public function givenSuggestionFeedbacks()
    {
        return $this->hasMany(SuggestionFeedback::class, 'admin_employee_id', 'employee_id');
    }



    // Payroll relation
    public function salaryGrade()
    {
        return $this->belongsTo(SalaryGrade::class);
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }



    //  BUSINESS LOGIC 

    public function getWorkingPeriodMonthsAttribute()
    {
        if (!$this->working_period_start)
            return 0;

        $start = Carbon::parse($this->working_period_start);
        $end = $this->working_period_end
            ? Carbon::parse($this->working_period_end)
            : now();

        return $start->diffInMonths($end);
    }

    //     public function getFormattedOvertimeDateAttribute(): string
    // {
    //     return $this->overtime_date->format('d M Y');
    // }

    public function getWorkingPeriodAttribute()
    {
        $months = $this->working_period_months;
        return intdiv($months, 12) . ' tahun ' . ($months % 12) . ' bulan';
    }

    public function isActive()
    {
        return is_null($this->working_period_end);
    }

    public function isPermanent()
    {
        return $this->employment_type === 'permanent';
    }

    public function isContract()
    {
        return $this->employment_type === 'contract';
    }


    public function isIntern()
    {
        return $this->employment_type === 'intern';
    }

    public function isFreelance()
    {
        return $this->employment_type === 'freelance';
    }

    public function isProbation()
    {
        return $this->employment_type === 'probation';
    }


    // 🔥 Unified position accessor (HRD + payroll)
    public function getPositionAttribute()
    {
        return $this->salaryGrade->position ?? $this->role;
    }
}