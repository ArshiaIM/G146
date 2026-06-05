<?php

namespace App\Livewire\Employee;

use App\Models\System\Organization;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Employee\Employee;
use App\Models\System\Division;
use App\Models\System\Battalion;
use App\Models\System\Unit;

class Create extends Component
{
    public $employee;
    public $first_name, $last_name, $age, $job_title, $education_level, $degree;
    public $address, $phone_primary, $phone_secondary, $phone_landline;
    public $division_id, $battalion_id, $unit_id, $organization_id;

    public $divisions = [];
    public $battalions = [];
    public $units = [];
    public $organizations = [];


    public $search = '';
    protected $queryString = ['search', 'division_id', 'battalion_id', 'unit_id'];
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'age' => 'nullable|integer|min:18|max:70',
        'division_id' => 'nullable|exists:divisions,id',
        'battalion_id' => 'nullable|exists:battalions,id',
        'unit_id' => 'nullable|exists:units,id',
        'organization_id' => 'nullable|exists:organizations,id',
    ];

    public function mount($employeeId = null)
    {
        $this->divisions = Division::all();
        $this->updatedDivisionId($this->division_id);
            $this->updatedBattalionId($this->battalion_id);
        $this->organizations = Organization::all();

        if ($employeeId) {
            $this->employee = Employee::findOrFail($employeeId);
            $this->fill($this->employee->toArray());
            $this->updatedDivisionId($this->division_id);
            $this->updatedBattalionId($this->battalion_id);
        }
        // همه‌ی Division ها را در ابتدای لود می‌گیریم
        $this->divisions = Division::orderBy('name')->get();
    }

    public function updatedDivisionId($value)
    {
        $this->battalions = Battalion::where('division_id', $value)->get();
        $this->units = collect();
        $this->battalion_id = null;
        $this->unit_id = null;
    }

    public function updatedBattalionId($value)
    {
        // وقتی Battalion عوض شد، فقط Unit های مربوط به همون Battalion لود می‌شن
        // $this->units = Unit::where('battalion_id', $value)
        //     ->orderBy('name')
        //     ->get();

        $this->units = Unit::where('battalion_id', $value)->get();
        $this->unit_id = null;
    }

    public function save()
    {
        $data = $this->validate();
        Employee::updateOrCreate(['id' => $this->employee->id ?? null], $data);

        session()->flash('success', 'Employee saved successfully!');
        return redirect()->route('employee.index');
    }



     #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.employee.create');
    }
}
