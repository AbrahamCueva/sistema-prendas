<x-app-layout>
    <x-slot name="title">
        {{ __('Editar Línea') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Línea: ') }}
            <span class="text-indigo-600 dark:text-indigo-400">
                {{ $stitchingLine->name }}
            </span>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('stitching-lines.update', $stitchingLine) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Nombre de la Línea
                            </label>
                            <input id="name" type="text" name="name"
                                value="{{ old('name', $stitchingLine->name) }}" required autofocus
                                class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700
                                       bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6 flex items-start bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border dark:border-gray-700">
                            <div class="flex items-center h-5">
                                <input id="is_external_service" name="is_external_service" type="checkbox" value="1"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600
                                           border-gray-300 dark:border-gray-600 rounded"
                                    {{ old('is_external_service', $stitchingLine->is_external_service) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_external_service" class="font-semibold text-gray-700 dark:text-gray-300">
                                    Es Servicio Externo (único)
                                </label>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Marque esta opción si esta línea representa el <b>Servicio Externo Único</b>.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-8 pt-4 border-t dark:border-gray-700">
                            <a href="{{ route('stitching-lines.index') }}"
                               class="mr-4 text-sm font-medium text-gray-600 dark:text-gray-300
                                      hover:text-gray-900 dark:hover:text-white transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-green-600 text-white font-bold rounded-xl
                                           hover:bg-green-700 transition shadow">
                                Actualizar Línea
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
