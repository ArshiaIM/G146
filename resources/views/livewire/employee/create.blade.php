<div class="py-12">
     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 m-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">👤 Request Store</h2>

    @if (session()->has('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('name')" />
                <x-text-input type="text" wire:model="name" class="border rounded-lg w-full p-2" />
                {{-- @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('lastname')" />
                <x-text-input type="text" wire:model="lastname" class="border rounded-lg w-full p-2" />
                {{-- @error('lastname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>
            <div>
                <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('lastname')" />
                <x-text-input type="text" wire:model="lastname" class="border rounded-lg w-full p-2" />
                {{-- @error('lastname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>
            <div>
                <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('lastname')" />
                <x-text-input type="text" wire:model="lastname" class="border rounded-lg w-full p-2" />
                {{-- @error('lastname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>
            <div>
            <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('Prossonel')" />
            <select wire:model="batlion_id" class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100">
                <option value="">Select employee...</option>
                {{-- @foreach($employees as $p)
                    <option value="{{ $p->id }}">{{ $p->rank }} {{ $p->first_name }} {{ $p->last_name }}</option>
                @endforeach --}}
            </select>
            {{-- @error('employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
        </div>
            <div>
            <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('Prossonel')" />
            <select wire:model="unit_id" class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100">
                <option value="">Select employee...</option>
                {{-- @foreach($employees as $p)
                    <option value="{{ $p->id }}">{{ $p->rank }} {{ $p->first_name }} {{ $p->last_name }}</option>
                @endforeach --}}
            </select>
            {{-- @error('employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
        </div>
        </div>

        <div>
            <x-input-label class="block text-sm font-medium text-gray-700 mb-1" :value="__('Reason')" />
            <textarea wire:model="reason" rows="3" class="border rounded-lg w-full p-2 bg-gray-800 text-gray-100"></textarea>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
            Submit Store
        </button>
    </form>
            </div>
    </div>

</div>

</div>
