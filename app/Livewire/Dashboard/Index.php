<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\System\Division;
use App\Models\System\Battalion;
use App\Models\System\Unit;
use App\Models\Employee\Employee;
use App\Models\Employee\Leave;

class Index extends Component
{
     public $totalDivisions, $totalBattalions, $totalUnits, $totalEmployees, $pendingLeaves;


    public function mount()
    {
        $this->totalDivisions = Division::count();
        $this->totalBattalions = Battalion::count();
        $this->totalUnits = Unit::count();
        $this->totalEmployees = Employee::count();
        $this->pendingLeaves = Leave::where('status', 'pending')->count();
    }



    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
