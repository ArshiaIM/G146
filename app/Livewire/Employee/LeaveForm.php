<?php

namespace App\Livewire\Employee;

use App\Models\Employee\Employee;
use App\Models\Employee\Leave;
use Livewire\Attributes\Layout;
use Livewire\Component;



class LeaveForm extends Component
{

    public $employee_id, $start_date, $end_date, $reason;

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'reason' => 'nullable|string|max:500',
    ];

    public function submit()
    {
        $this->validate();

        Leave::create([
            'employee_id' => $this->employee_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        $this->reset();
        session()->flash('success', '✅ Leave request submitted successfully.');
    }

    #[Layout('layouts.app')]
    public function render()
    {

        return view('livewire.employee.leave',['employees'=> Employee::all()]);
        // , [
        //     'employees' => Employee::orderBy('rank')->get(),
        // ]
    }
}
