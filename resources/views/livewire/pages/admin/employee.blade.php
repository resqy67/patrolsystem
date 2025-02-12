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
                            <h1 class="text-2xl font-semibold text-gray-800">Daftar User</h1>
                        </div>
                        <div>
                            <x-mary-modal wire:model="showModal" class="backdrop-blur" subtitle="Livewire example"
                                separator title="Tambah Data">
                                <x-mary-form wire:submit="save">
                                    <x-mary-input label="Nama" wire:model="form.name" />
                                    <x-mary-input label="Email" wire:model="form.email" />
                                    <x-mary-input label="Password" wire:model="form.password" type="password" />
                                    <x-mary-select label="Role" wire:model="form.role" :options="$selectedRole" option-value="key" placeholder-value="null" placeholder="Select a user" />
                                    {{-- <x-mary-input label="Role" wire:model="form.role" /> --}}
                                    <x-slot:actions>
                                        <x-mary-button label="Cancel" @click="$wire.showModal = false" />
                                        <x-mary-button label="Save" class="btn-primary" type="submit"
                                            spinner="save" />
                                    </x-slot:actions>
                                </x-mary-form>
                            </x-mary-modal>
                            <x-mary-button class="bg-blue-500 text-white" label="Tambah" @click="$wire.showModal = true" />
                        </div>
                    </div>
                    <x-mary-table :headers="$headers" :rows="$users" striped>
                        @scope('cell_action', $user)
                            <x-mary-button class="bg-blue-500 text-white" label="Edit" @click="$wire.modalUpdate({{ $user->id }})" />
                            <x-mary-button class="bg-red-500 text-white" label="Delete" @click="$wire.modalDelete({{ $user->id }})" />
                        @endscope
                    </x-mary-table>
                    <x-mary-modal wire:model="showDelete" class="backdrop-blur" title="Hapus Data" separator>
                        <div>Kamu yakin menghapus data ini ?</div>

                        <x-slot:actions>
                            <x-mary-button label="Cancel" @click="$wire.showDelete = false" />
                            <x-mary-button label="Confirm" class="btn-primary" wire:click="delete" spinner="delete" />
                        </x-slot:actions>
                    </x-mary-modal>
                    <x-mary-modal wire:model="showUpdate" class="backdrop-blur" subtitle="Livewire example"
                                separator title="Edit Data">
                                <x-mary-form wire:submit="update">
                                    <x-mary-input label="Nama" wire:model="name" />
                                    <x-mary-input label="Email" wire:model="email" />
                                    <x-mary-input label="Password" wire:model="password" type="password" />
                                    <x-mary-select label="Role" wire:model="role" :options="$selectedRole" option-value="key" placeholder-value="null" placeholder="Select a user" />
                                    {{-- <x-mary-input label="Role" wire:model="form.role" /> --}}
                                    <x-slot:actions>
                                        <x-mary-button label="Cancel" @click="$wire.showModal = false" />
                                        <x-mary-button label="Save" class="btn-primary" type="submit"
                                            spinner="update" />
                                    </x-slot:actions>
                                </x-mary-form>
                            </x-mary-modal>
                </div>
            </div>
        </div>
    </div>
</div>
