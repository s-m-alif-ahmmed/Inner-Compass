<x-filament-panels::page>
    <!-- Profile Form -->
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="pt-4">
            <x-filament::button type="submit">
                Update
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
