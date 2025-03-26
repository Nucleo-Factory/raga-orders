<div class="space-y-7">
    <form action="" class="space-y-10 rounded-2xl bg-white p-8">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-[#7288FF]">Etapas para PO's</h2>

            <div class="space-x-4">
                <x-secondary-button class="w-[180px]">
                    Cancelar
                </x-secondary-button>
                <x-primary-button class="w-[180px]" type="submit">
                    Guardar cambios
                </x-primary-button>
            </div>
        </div>

        <div>
            <div class="flex items-end gap-8">
                <x-form-input class="w-1/4">
                    <x-slot:label>
                        Etapa #1
                    </x-slot:label>
                    <x-slot:input name="" placeholder="Etapa #1" wire:model="">
                    </x-slot:input>
                </x-form-input>

                <x-form-input class="grow">
                    <x-slot:input name="" placeholder="Etapa #1" wire:model="">
                    </x-slot:input>
                </x-form-input>
            </div>
        </div>
    </form>

    <form action="" class="space-y-10 rounded-2xl bg-white p-8">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-[#7288FF]">Etapas para Agrupaciones</h2>

            <div class="space-x-4">
                <x-secondary-button class="w-[180px]">
                    Cancelar
                </x-secondary-button>
                <x-primary-button class="w-[180px]" type="submit">
                    Guardar cambios
                </x-primary-button>
            </div>
        </div>

        <div>
            <div class="flex items-end gap-8">
                <x-form-input class="w-1/4">
                    <x-slot:label>
                        Etapa #1
                    </x-slot:label>
                    <x-slot:input name="" placeholder="Etapa #1" wire:model="">
                    </x-slot:input>
                </x-form-input>

                <x-form-input class="grow">
                    <x-slot:input name="" placeholder="Etapa #1" wire:model="">
                    </x-slot:input>
                </x-form-input>
            </div>
        </div>
    </form>
</div>
