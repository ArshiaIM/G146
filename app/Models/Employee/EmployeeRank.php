<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeRank extends Model
{
    protected $fillable = [
        'name',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
