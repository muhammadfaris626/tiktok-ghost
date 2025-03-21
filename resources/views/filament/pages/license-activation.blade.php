<x-filament-panels::page>
    <h1 class="text-center text-2xl font-bold mb-6">LICENSE ACTIVATION</h1>
    <div class="flex items-start justify-center min-h-screen pt-20">
        <form wire:submit.prevent="submit" class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            {{ $this->form }}
            <div class="pt-4 text-center">
                <x-filament::button type="submit">Submit</x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
