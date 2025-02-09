<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">Patrol</h1>
                        </div>
                    </div>
                    <x-mary-table :headers="$headers" :rows="$users" striped>
                        @scope('cell_action', $user)
                            {{-- <x-mary-button class="bg-blue-500 text-white" label="Edit" @click="editUser({{ $user->id }})" />
                            <x-mary-button class="bg-red-500 text-white" label="Delete" @click="deleteUser({{ $user->id }})" /> --}}
                        @endscope
                    </x-mary-table>
                </div>
            </div>
        </div>
    </div>
</div>
