<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col sm:flex-row sm:flex-wrap lg:flex-nowrap justify-between gap-4">
                    <x-mary-stat title="All Report" description="All report are entered" value="{{ $reported }}" icon="o-clipboard-document-list" />

                    <x-mary-stat title="Resolved" description="Report has Resolved" value="{{ $resolved }}" icon="o-clipboard-document-check" />

                    <x-mary-stat title="Open" description="Report stat Open" value="{{ $unresolved }}" icon="o-document-magnifying-glass"
                        class="text-orange-500" color="text-pink-500" />
                </div>
            </div>
        </div>
    </div>
</div>
