{{-- resources/views/clients/index.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        {{ __('Gestión de Clientes') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Gestión de Clientes (Marcas)') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Botón Registrar --}}
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('clients.create') }}"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                            Registrar Nuevo Cliente
                        </a>
                    </div>

                    {{-- Mensajes (Se mantiene el estilo de borde lateral) --}}
                    @if (session('success'))
                        <div
                            class="bg-green-200 dark:bg-green-900 border-l-4 border-green-600 text-green-800 dark:text-green-200 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-200 dark:bg-red-900 border-l-4 border-red-600 text-red-800 dark:text-red-200 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Tabla --}}
                    <div class="overflow-x-auto rounded-lg border dark:border-gray-700 shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800"> {{-- Cambiado de gray-200 a gray-50 para mejor contraste --}}
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre de Marca
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($clients as $client)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                        {{-- ID --}}
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-200">
                                            {{ $client->id }}
                                        </td>

                                        {{-- NOMBRE DE MARCA --}}
                                        <td class="px-6 py-4 text-base font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $client->name }}
                                        </td>

                                        {{-- ACCIONES --}}
                                        <td class="px-6 py-4 text-sm font-medium text-center space-x-3">
                                            <a href="{{ route('clients.edit', $client) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                Editar
                                            </a>

                                            {{-- BOTÓN QUE ACTIVA EL MODAL (Reemplaza la alerta simple) --}}
                                            <button type="button" x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-client-deletion-{{ $client->id }}')"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 ml-3">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6 text-gray-900 dark:text-gray-100">
                        {{ $clients->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @foreach ($clients as $client)
        {{-- INICIO MODAL DE ELIMINACIÓN --}}
        <x-modal name="confirm-client-deletion-{{ $client->id }}" focusable>
            <form method="post" action="{{ route('clients.destroy', $client) }}" class="p-6">
                @csrf
                @method('delete')

                {{-- Contenedor del Título y Alerta --}}
                <div class="flex items-start pb-4 border-b dark:border-gray-700">
                    {{-- ÍCONO DE ALERTA (Visual Aid) --}}
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

                    {{-- TÍTULO --}}
                    <div class="mt-0 ml-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Confirmar Eliminación: Cliente
                        </h2>
                    </div>
                </div>

                {{-- CUERPO DEL MENSAJE --}}
                <div class="mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        ¿Estás seguro de que quieres **eliminar** al cliente **{{ $client->name }}**?
                    </p>

                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-semibold">
                        Detalles del Cliente:
                        <span class="font-normal block ml-2">ID: {{ $client->id }}</span>
                    </p>

                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        Esta acción es **irreversible**. Al eliminar un cliente, todos los lotes de prendas asociados
                        perderán la referencia de la marca.
                    </p>
                </div>

                {{-- BOTONES --}}
                <div class="mt-6 flex justify-end pt-4 border-t dark:border-gray-700">
                    <x-secondary-button x-on:click="$dispatch('close')" class="mr-3">
                        Cancelar
                    </x-secondary-button>

                    <x-danger-button>
                        Eliminar Cliente
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
        {{-- FIN MODAL DE ELIMINACIÓN --}}
    @endforeach
</x-app-layout>
