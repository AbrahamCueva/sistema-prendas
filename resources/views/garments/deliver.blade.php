{{-- resources/views/garments/deliver.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Confirmar Entrega y Devolución de Prenda') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">

                <div class="p-6 border-b dark:border-gray-700">

                    {{-- TÍTULO --}}
                    <h3 class="text-2xl font-bold mb-3 text-green-600 dark:text-green-400">
                        Prenda PV: {{ $garment->pv }}
                    </h3>

                    {{-- INFO --}}
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Marca: <span class="font-semibold">{{ $garment->client->name }}</span> |
                        Color: <span class="font-semibold">{{ $garment->color }}</span>
                    </p>

                    {{-- MOTIVO --}}
                    <div class="mb-6 p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700">
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300">
                            Motivo de Arreglo:
                        </p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            {{ $garment->motive->name }} (Proceso: {{ ucfirst($garment->motive->type) }})
                        </p>
                    </div>

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('garments.deliver', $garment) }}">
                        @csrf
                        @method('PUT')

                        {{-- PERSONA QUE RECIBE --}}
                        <div class="mb-6">
                            <label for="received_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre de la Persona que Recibe
                            </label>

                            <input id="received_by" type="text" name="received_by"
                                value="{{ old('received_by', $garment->delivered_by) }}"
                                required
                                placeholder="Ej: Personal de almacén"
                                class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">

                            @error('received_by')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- FECHA --}}
                        <div class="mb-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700">
                            <p class="font-bold text-blue-800 dark:text-blue-300">
                                Fecha y Hora de Devolución:
                            </p>
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                {{ now()->format('d/M/Y H:i:s') }} (Se registrará al guardar)
                            </p>
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex items-center justify-between mt-8 pt-4 border-t dark:border-gray-700">

                            <a href="{{ route('garments.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                Cancelar
                            </a>

                            <button type="submit"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition shadow-lg">
                                Confirmar ENTREGA y Cerrar Caso
                            </button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
