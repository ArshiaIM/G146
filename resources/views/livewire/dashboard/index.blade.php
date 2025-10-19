<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="space-y-8">

    <h1 class="text-3xl font-bold text-gray-800">🏛️ Dashboard Overview</h1>

    <!-- Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <x-dashboard.card title="Divisions" value="{{ $totalDivisions }}" color="blue" />
        <x-dashboard.card title="Battalions" value="{{ $totalBattalions }}" color="green" />
        <x-dashboard.card title="Units" value="{{ $totalUnits }}" color="purple" />
        <x-dashboard.card title="Personnel" value="{{ $totalPersonnel }}" color="yellow" />
        <x-dashboard.card title="Pending Leaves" value="{{ $pendingLeaves }}" color="red" />
    </div>

    <div>
        <livewire:personnel.leave-form />
    </div>

</div>

</div>
