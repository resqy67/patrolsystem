<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tabel Patrol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">Patrol</h1>
                        </div>
                        <div>
                            <x-mary-modal wire:model="showModal" class="backdrop-blur" subtitle="Livewire example"
                                separator title="Tambah Data">
                                <x-mary-form wire:submit="save">
                                    <x-mary-input label="Nama Perusahaan" wire:model="form.company_name" />
                                    <x-mary-input label="Lokasi" wire:model="form.location" />
                                    <x-mary-input label="Deskripsi Masalah" wire:model="form.description_of_problem" />
                                    <x-mary-datepicker label="Tanggal Close" wire:model="form.date_to_be_resolved"
                                        :config="$config1" />
                                    <x-mary-file wire:model="form.image_path" label="Gambar Sebelum" hint="Only Image"
                                        accept="image/png, image/jpeg" />
                                    <x-slot:actions>
                                        <x-mary-button label="Cancel" @click="$wire.showModal = false" />
                                        <x-mary-button label="Save" class="btn-primary" type="submit"
                                            spinner="save" />
                                    </x-slot:actions>
                                </x-mary-form>
                            </x-mary-modal>
                            <x-mary-modal wire:model="showExport" class="backdrop-blur" subtitle="Livewire example"
                                separator title="Export PDF">
                                <x-mary-form wire:submit="export">
                                    <x-mary-datepicker label="Dari Bulan" wire:model="date_range_start"
                                        :config="$config1" />
                                    <x-mary-datepicker label="Sampai Bulan" wire:model="date_range_end"
                                        :config="$config1" />
                                    <x-slot:actions>
                                        <x-mary-button label="Cancel" @click="$wire.showExport = false" />
                                        <x-mary-button label="Export" class="btn-primary" type="submit"
                                            spinner="export" />
                                    </x-slot:actions>
                                </x-mary-form>
                            </x-mary-modal>
                            <x-mary-button class="bg-blue-500 text-white" label="Tambah"
                                @click="$wire.showModal = true" />
                            <x-mary-button class="bg-blue-500 text-white" label="Export PDF" @click="$wire.showExport = true" />
                        </div>
                    </div>
                    <div data-theme="light">
                        @if ($reports->isEmpty())
                            <div class="text-center text-gray-500">Data tidak ditemukan</div>
                        @else
                            <x-mary-table :headers="$headers" :rows="$reports" no-hover with-pagination>
                                @scope('cell_name', $report)
                                    {{ $report->user->name }}
                                @endscope
                                @scope('cell_status', $report)
                                    @php
                                        $buttonClass = $report->status === 'open' ? 'badge-warning' : 'badge-primary';
                                    @endphp
                                    <x-mary-badge :value="$report->status" :class="$buttonClass" />
                                @endscope
                                @scope('cell_image_before', $report)
                                    @foreach ($report->images as $image)
                                        @if ($image->is_before)
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Before Image"
                                                class="w-32 h-32 object-cover hover-zoom cursor-pointer" />
                                        @endif
                                    @endforeach
                                @endscope
                                @scope('cell_image_after', $report)
                                    @foreach ($report->images as $image)
                                        @if ($image->is_before === 0)
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="After Image"
                                                class="w-32 h-32 object-cover hover-zoom cursor-pointer" />
                                        @endif
                                    @endforeach
                                @endscope
                                @scope('cell_action', $report)
                                    @if ($report->status === 'open')
                                        <x-mary-button class="bg-blue-500 text-white" label="Selesai"
                                            @click="$wire.confirmFinish({{ $report->id }})" />
                                    @else
                                        <x-mary-button class="bg-green-500 text-white" label="Selesai" disabled />
                                    @endif
                                    {{-- <x-mary-button class="bg-blue-500 text-white" label="Selesai"
                                        @click="$wire.confirmFinish({{ $report->id }})" /> --}}
                                    @if (auth()->user()->role === 'admin')
                                        <x-mary-button class="btn-error text-white" label="Delete"
                                            @click="$wire.confirmDelete({{ $report->id }})" />
                                    @endif
                                    {{-- <x-mary-button class="  " label="Edit" /> --}}
                                @endscope
                            </x-mary-table>
                        @endif
                    </div>
                    <x-mary-modal wire:model="showFinish" class="backdrop-blur" title="Selesai Laporan" separator>
                        <x-mary-form wire:submit="resolveReport">
                            <x-mary-file wire:model="image_path" label="Gambar Sesudah" hint="Only Image"
                                accept="image/png, image/jpeg" />
                            <x-mary-datepicker label="Tanggal Close" wire:model="date_resolved" :config="$config1" />
                            <x-slot:actions>
                                <x-mary-button label="Cancel" @click="$wire.showFinish = false" />
                                <x-mary-button label="Save" class="btn-primary" type="submit"
                                    spinner="resolveReport" />
                            </x-slot:actions>
                        </x-mary-form>
                    </x-mary-modal>
                    <x-mary-modal wire:model="showDelete" class="backdrop-blur" title="Hapus Data" separator>
                        <div>Kamu yakin menghapus data ini ?</div>

                        <x-slot:actions>
                            <x-mary-button label="Cancel" @click="$wire.showDelete = false" />
                            <x-mary-button label="Confirm" class="btn-primary" wire:click="delete" spinner="delete" />
                        </x-slot:actions>
                    </x-mary-modal>
                    <x-mary-modal wire:model="showImage" class="backdrop-blur" title="Full Image" separator>
                        <img src="{{ $selectedImage }}" alt="Full Image" class="w-full h-auto" />
                        <x-slot:actions>
                            <x-mary-button label="Close" @click="$wire.showImage = false" />
                        </x-slot:actions>
                    </x-mary-modal>
                </div>
            </div>
        </div>
    </div>
