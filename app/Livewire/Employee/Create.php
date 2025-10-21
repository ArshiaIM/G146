<?php

namespace App\Livewire\Employee;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
     #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.employee.create');
    }
}
