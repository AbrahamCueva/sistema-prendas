<x-app-layout>
    <x-slot name="title">
        {{ __('Gestión de Líneas de costura') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Líneas de Costura / Servicio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- BOTÓN SUPERIOR --}}
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('stitching-lines.create') }}"
                           class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow">
                            Registrar Nueva Línea
                        </a>
                    </div>

                    {{-- MENSAJES --}}
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700
                                    text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700
                                    text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nombre de la Línea
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($lines as $line)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-200">
                                            {{ $line->id }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $line->name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            @if($line->is_external_service)
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                                             bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">
                                                    Servicio Externo
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                                             bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                                    Costura Interna
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-sm font-medium">

                                            <a href="{{ route('stitching-lines.edit', $line) }}"
                                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                                                Editar
                                            </a>

                                            <form action="{{ route('stitching-lines.destroy', $line) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta línea?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
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
                        {{ $lines->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
