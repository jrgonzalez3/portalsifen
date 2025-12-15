<x-filament-widgets::widget>
    <x-filament::section>
        <form wire:submit="consult" class="flex flex-col md:flex-row items-end gap-4 w-full">
            
            <!-- RUC Input -->
            <div class="flex-grow w-full md:w-auto">
                <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white block mb-2" for="ruc_number">
                    Consultar RUC
                </label>
                <div class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 overflow-hidden bg-white dark:bg-gray-900 focus-within:ring-2 focus-within:ring-primary-600">
                    <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">
                        <x-heroicon-m-identification style="width: 20px; height: 20px;" class="text-gray-400" />
                    </span>
                    <input 
                        type="text" 
                        wire:model="ruc_number" 
                        id="ruc_number" 
                        class="block flex-1 border-0 bg-transparent py-2 pl-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 dark:text-white" 
                        placeholder="Ingrese RUC (Sin DV)"
                    >
                </div>
                @error('ruc_number') <div class="text-danger-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Environment Radio (Standard but styled) -->
            <div class="flex-none">
                 <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white block mb-2">
                    Entorno
                 </label>
                 <div class="flex gap-3 h-[40px] items-center">
                     <label class="inline-flex items-center cursor-pointer">
                         <input type="radio" wire:model="environment" value="test" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700">
                         <span class="ml-2 text-sm text-gray-900 dark:text-gray-300">Testing</span>
                     </label>
                     <label class="inline-flex items-center cursor-pointer">
                         <input type="radio" wire:model="environment" value="prod" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700">
                         <span class="ml-2 text-sm text-gray-900 dark:text-gray-300">Producción</span>
                     </label>
                 </div>
            </div>

            <!-- Button -->
            <div class="flex-none pb-[2px]">
                <x-filament::button type="submit" size="lg" icon="heroicon-m-magnifying-glass">
                    Consultar
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
