{{-- resources/views/clients/edit.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        {{ __('Editar Cliente') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Editar Cliente (Marca): ' . $client->name) }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-950">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg rounded-xl">
                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('clients.update', $client) }}">
                        @csrf
                        @method('PUT')

                        {{-- Campo Nombre --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                                Nombre de la Marca
                            </label>
                            <input id="name" type="text" name="name"
                                value="{{ old('name', $client->name) }}" required autofocus
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-700
                                       bg-white dark:bg-gray-800
                                       text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">

                            @error('name')
                                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end gap-4 mt-8">
                            <a href="{{ route('clients.index') }}"
                               class="text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                Cancelar
                            </a>

                            <button type="submit"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                                Actualizar Marca
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
