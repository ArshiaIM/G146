
    <x-slot name="header">
        <h2 class="text-xl font-bold mb-4 dark:text-gray-200">
        {{ $employee ? 'Edit Employee' : 'Add New Employee' }}
    </h2>
    </x-slot>

    <div class="px-12">
    <div class="max-w-7xl m-12 mx-auto sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-2xl dark:bg-gray-900 dark:text-gray-200">


    <form wire:submit.prevent="save" class="space-y-4 dark:bg-gray-900 dark:text-gray-200">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label :value="__('FirstName')" />
                <x-text-input type="text" wire:model="first_name" class="w-full input" />
                @error('first_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-input-label :value="__('LastName')" />
                <x-text-input type="text" wire:model="last_name" class="w-full input" />
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <x-input-label :value="__('Age')" />
                <x-text-input type="number" wire:model="age" class="w-full input" />
            </div>
            <div>
                <label>Job Title</label>
                <x-text-input type="text" wire:model="job_title" class="w-full input" />
            </div>
            <div>
                <label>Degree</label>
                <x-text-input type="text" wire:model="degree" class="w-full input" />
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label>Division</label>
                <select wire:model="division_id" lastnam class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100"e>
                    <option value="">-- Select Division --</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>

                <label>Battalion</label>
                <select wire:model="battalion_id" lastnam class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100"e>
                    <option value="">-- Select Battalion --</option>
                    @foreach($battalions as $battalion)
                        <option value="{{ $battalion->id }}">{{ $battalion->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Unit</label>
                <select wire:model="unit_id" lastnam class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100"e>
                    <option value="">-- Select Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label>Organization (Optional)</label>
            <select wire:model="organization_id" lastnam class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100"e>
                <option value="">-- None --</option>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary w-full mt-4">Save</button>
    </form>
</div>

    </div>
    </div>
</div>
