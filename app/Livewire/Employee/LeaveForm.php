<?php

namespace App\Livewire\Employee;

use App\Models\Employee\Employee;
use App\Models\Employee\Leave;
use Livewire\Component;



class LeaveForm extends Component
{

    public $personnel_id, $start_date, $end_date, $reason;

    protected $rules = [
        'personnel_id' => 'required|exists:personnels,id',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'reason' => 'nullable|string|max:500',
    ];

    public function submit()
    {
        $this->validate();

        Leave::create([
            'personnel_id' => $this->personnel_id,
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
        return view('livewire.employee.leave',['personnels'=> Employee::all()])->layout('layouts.app');
        // , [
        //     'personnels' => Employee::orderBy('rank')->get(),
        // ]
    }
}
