{{-- resources/views/clients/index.blade.php --}}
<x-app-layout>
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

                    {{-- Mensajes --}}
                    @if (session('success'))
                        <div class="bg-green-200 dark:bg-green-900 border-l-4 border-green-600 text-green-800 dark:text-green-200 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-200 dark:bg-red-900 border-l-4 border-red-600 text-red-800 dark:text-red-200 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Tabla --}}
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">
                                        Nombre de Marca
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($clients as $client)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-sm font-semibold">
                                            {{ $client->id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $client->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-center space-x-3">
                                            <a href="{{ route('clients.edit', $client) }}"
                                               class="text-indigo-500 hover:text-indigo-300">
                                                Editar
                                            </a>

                                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-500 hover:text-red-300">
                                                    Eliminar
                                                </button>
                                            </form>
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
</x-app-layout>
