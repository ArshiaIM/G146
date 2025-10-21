<div>

    {{-- The best athlete wants his opponent at his best. --}}
    <div class="space-y-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

         <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shomaresh') }}
        </h2>
    </x-slot>
    </div>
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Cards -->
        <div class="flex gap-6 m-8">
            <x-dashboard.card title="Divisions" value="{{ $totalDivisions }}" color="blue" />
            <x-dashboard.card title="Battalions" value="{{ $totalBattalions }}" color="green" />
            <x-dashboard.card title="Units" value="{{ $totalUnits }}" color="purple" />
            <x-dashboard.card title="Employees" value="{{ $totalEmployees }}" color="yellow" link="{{ route('employee.store') }}"/>
            <x-dashboard.card title="Pending Leaves" value="{{ $pendingLeaves }}" color="red" link="{{ route('employee.leaves') }}"/>
        </div>
        </div>
</div>


</div>
</div>
