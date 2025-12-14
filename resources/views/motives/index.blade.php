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
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('motives.create') }}"
                            class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-md">
                            Registrar Nuevo Motivo
                        </a>
                    </div>
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
                    <div class="overflow-x-auto rounded-xl border dark:border-gray-700 shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800"> {{-- Fondo más claro para el encabezado --}}
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre del Motivo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($motives as $motive)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $motive->id }}
                                        </td>
                                        <td class="px-6 py-4 text-base font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $motive->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @php
                                                $type = strtolower($motive->type);
                                                $typeText = ucfirst($motive->type);
                                                $colorClass = match ($type) {
                                                    'costura' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
                                                    'bordado' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
                                                    'estampado' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/40 dark:text-pink-300',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                };
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $colorClass }}">
                                                {{ $typeText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium space-x-3">
                                            <a href="{{ route('motives.edit', $motive) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                                Editar
                                            </a>
                                            <button type="button" x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-motive-deletion-{{ $motive->id }}')"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:underline">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $motives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($motives as $motive)
        <x-modal name="confirm-motive-deletion-{{ $motive->id }}" focusable>
            <form method="post" action="{{ route('motives.destroy', $motive) }}" class="p-6">
                @csrf
                @method('delete')
                <div class="flex items-start pb-4 border-b dark:border-gray-700">
                    <div
                        class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/40 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.375l1.458 2.518c.426.736 1.151 1.036 1.96 1.036h12.19c.809 0 1.534-.3 1.96-1.036l1.458-2.518A1.82 1.82 0 0021.082 14.5H2.918a1.82 1.82 0 00-.935 1.625z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21.75a9.75 9.75 0 009.75-9.75h-19.5A9.75 9.75 0 0012 21.75z" />
                        </svg>
                    </div>
                    <div class="mt-0 ml-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Confirmar Eliminación: Motivo
                        </h2>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        ¿Estás seguro de que quieres **eliminar** el motivo
                        **{{ $motive->name }}**?
                    </p>
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-semibold">
                        Detalles del Motivo:
                        <span class="font-normal block ml-2">ID: {{ $motive->id }}
                            | Tipo: {{ ucfirst($motive->type) }}</span>
                    </p>

                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        Esta acción es **irreversible** y puede causar inconsistencias si
                        hay lotes activos asociados a este motivo.
                    </p>
                </div>
                <div class="mt-6 flex justify-end pt-4 border-t dark:border-gray-700">
                    <x-secondary-button x-on:click="$dispatch('close')" class="mr-3">
                        Cancelar
                    </x-secondary-button>
                    <x-danger-button>
                        Eliminar Motivo
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-app-layout>
