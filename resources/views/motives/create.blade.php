{{-- resources/views/motives/create.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        {{ __('Registrar Nuevo Motivo de Arreglo') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Nuevo Motivo de Arreglo') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('motives.store') }}">
                        @csrf

                        {{-- CAMPO NOMBRE --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Descripción del Motivo
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Ej: Mal cosido, Falta bordado, Falta goma de estampado"
                                class="mt-2 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800
                                       dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >

                            @error('name')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- CAMPO TIPO --}}
                        <div class="mb-6">
                            <label for="type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Tipo de Proceso
                            </label>

                            <select
                                id="type"
                                name="type"
                                required
                                class="mt-2 block w-full rounded-xl border-gray-300 dark:border-gray-700
                                       dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500
                                       focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="" class="text-gray-500">Selecciona un tipo</option>

                                @foreach($motiveTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>

                            @error('type')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex items-center justify-end mt-8 pt-4 border-t dark:border-gray-700">

                            <a href="{{ route('motives.index') }}"
                               class="mr-4 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                Cancelar
                            </a>

                            <button
                                type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-xl
                                       hover:bg-indigo-700 transition shadow-md"
                            >
                                Registrar Motivo
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
