<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
                <div class="overflow-x-auto text-center">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    درجه
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    نام
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    نشان
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    اعزامی
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    نوع خدمت
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    گروهان
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4">{{ $user->rank->name }}</td>
                                    <td class="px-6 py-4">{{ $user->first_name }}</td>
                                    <td class="px-6 py-4">{{ $user->last_name }}</td>
                                    <td class="px-6 py-4">{{ $user->created_at_jalali}}</td>
                                    <td class="px-6 py-4">{{ $user->type->name }}</td>
                                    <td class="px-6 py-4">{{ $user->unit->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
