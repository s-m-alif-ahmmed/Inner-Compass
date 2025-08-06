<x-filament-panels::page>
    <!-- Password Change Form -->
    <form wire:submit.prevent="savePassword">
        {{ $this->form }}
        <div class="pt-4">
            <x-filament::button type="submit">
                Change Password
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
