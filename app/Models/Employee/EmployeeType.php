<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $fillable = ['name'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
