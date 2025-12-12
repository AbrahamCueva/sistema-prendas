{{-- resources/views/motives/index.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        {{ __('Gestión de Motivos de Arreglo') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Motivos de Arreglo') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- BOTÓN SUPERIOR --}}
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('motives.create') }}"
                            class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-md">
                            Registrar Nuevo Motivo
                        </a>
                    </div>

                    {{-- MENSAJES --}}
                    @if (session('success'))
                        <div
                            class="bg-green-100 dark:bg-green-900/40 border border-green-400 dark:border-green-700
                            text-green-800 dark:text-green-300 px-4 py-3 rounded-xl mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-100 dark:bg-red-900/40 border border-red-400 dark:border-red-700
                            text-red-800 dark:text-red-300 px-4 py-3 rounded-xl mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                        ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                        Nombre del Motivo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                        Tipo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($motives as $motive)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                                        {{-- ID --}}
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $motive->id }}
                                        </td>

                                        {{-- NOMBRE --}}
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $motive->name }}
                                        </td>

                                        {{-- TIPO --}}
                                        <td class="px-6 py-4 text-sm">
                                            @php
                                                $type = $motive->type;
                                                $typeText = $motiveTypes[$type] ?? $type;

                                                $colorClass = match ($type) {
                                                    'costura'
                                                        => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
                                                    'bordado'
                                                        => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
                                                    'estampado'
                                                        => 'bg-pink-100 text-pink-800 dark:bg-pink-900/40 dark:text-pink-300',
                                                    default
                                                        => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                };
                                            @endphp

                                            <span
                                                class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $colorClass }}">
                                                {{ $typeText }}
                                            </span>
                                        </td>

                                        {{-- ACCIONES --}}
                                        <td class="px-6 py-4 text-sm font-medium space-x-3">

                                            <a href="{{ route('motives.edit', $motive) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Editar
                                            </a>

                                            <form action="{{ route('motives.destroy', $motive) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Estás seguro de que quieres eliminar este motivo?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:underline">
                                                    Eliminar
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="mt-6">
                        {{ $motives->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
