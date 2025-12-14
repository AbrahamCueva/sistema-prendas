<x-app-layout>
    <x-slot name="title">
        {{ __('Añadir Stock a Lote Existente') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <i class="fas fa-layer-group text-blue-500"></i>
            {{ __('Añadir Stock al Lote PV:') }}
            <span class="text-blue-600 dark:text-blue-400 text-3xl">{{ $garment->pv }}</span>
        </h2>
    </x-slot>
    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 border-b dark:border-gray-700">

                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Utiliza este formulario para registrar una **entrada adicional** de prendas al lote existente.
                    </p>

                    <div class="mb-6 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700">
                        <p class="font-bold text-indigo-800 dark:text-indigo-300">
                            Detalle del Lote
                        </p>
                        <ul class="text-sm text-indigo-700 dark:text-indigo-200 mt-1 space-y-1">
                            <li><span class="font-medium">Marca:</span> {{ $garment->client->name }}</li>
                            <li><span class="font-medium">Tallas:</span> {{ $garment->sizes }}</li>
                            <li><span class="font-medium">Color:</span> {{ $garment->color }}</li>
                            <li><span class="font-medium">Línea:</span> {{ $garment->stitchingLine->name }}</li>
                            <li class="pt-2 text-base font-bold text-green-600 dark:text-green-400">
                                Cantidad Actual: {{ $garment->quantity_in }} prendas
                            </li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('garments.add-stock.process', $garment) }}">
                        @csrf
                        <div class="mb-6">
                            <label for="quantity_added" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                Cantidad de Prendas a AÑADIR al Lote
                            </label>
                            <input id="quantity_added" type="number" name="new_quantity"
                                value="{{ old('quantity_added', 1) }}"
                                required min="1"
                                class="mt-1 block w-full text-2xl p-4 rounded-xl border-blue-300 dark:border-blue-600
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                shadow-sm focus:border-blue-500 focus:ring-blue-500">

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Esta cantidad se sumará a la cantidad total (Entrada) del lote.
                            </p>
                            @error('quantity_added')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-4 border-t dark:border-gray-700">
                            <a href="{{ route('garments.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-lg flex items-center gap-2">
                                <i class="fas fa-plus-square"></i> Confirmar Adición de Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
