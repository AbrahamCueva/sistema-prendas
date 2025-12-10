<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registro y Seguimiento de Prendas') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-200">

                    {{-- HEADER & BOTÓN REGISTRO --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-semibold">Listado de Lotes de Prendas</h3>

                        <a href="{{ route('garments.create') }}"
                            class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow">
                            <i class="fas fa-plus"></i> Registrar ENTRADA
                        </a>
                    </div>

                    {{-- ALERTAS (mantener aquí) --}}
                    @if (session('success'))
                        <div
                            class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-800 dark:text-green-200 p-4 mb-4 rounded">
                            <p class="font-bold">Éxito</p>
                            <p>{!! session('success') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div
                            class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-800 dark:text-red-200 p-4 mb-4 rounded">
                            <p class="font-bold">Error</p>
                            <p>{!! session('error') !!}</p>
                        </div>
                    @endif
                    @if (session('warning'))
                        <div
                            class="bg-orange-100 dark:bg-orange-900 border-l-4 border-orange-500 text-orange-800 dark:text-orange-200 p-4 mb-4 rounded">
                            <p class="font-bold">Atención</p>
                            <p>{!! session('warning') !!}</p>
                        </div>
                    @endif

                    {{-- FORMULARIO DE BÚSQUEDA AVANZADA --}}
                    <form method="GET" action="{{ route('garments.index') }}"
                        class="mb-6 p-4 border dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-inner">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">

                            {{-- Filtro PV --}}
                            <div>
                                <label for="search_pv" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PV</label>
                                <input type="text" name="search_pv" id="search_pv"
                                    value="{{ request('search_pv') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            {{-- Filtro Cliente --}}
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                                <select name="client_id" id="client_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos los Clientes</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filtro Estado --}}
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="todos" {{ request('status') == 'todos' ? 'selected' : '' }}>Todos
                                    </option>
                                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>
                                        Entregado</option>
                                </select>
                            </div>

                            {{-- Filtro Fecha Desde --}}
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Desde</label>
                                <input type="date" name="date_from" id="date_from"
                                    value="{{ request('date_from') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            {{-- Filtro Fecha Hasta --}}
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Hasta</label>
                                <input type="date" name="date_to" id="date_to"
                                    value="{{ request('date_to') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                        </div>

                        {{-- Botones de Acción (Buscar y Limpiar) --}}
                        <div class="mt-4 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-md font-medium transition shadow-md">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('garments.index') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition shadow-md">
                                <i class="fas fa-sync-alt"></i> Limpiar Filtros
                            </a>

                            {{-- Botón Exportar a Excel --}}
                            @php
                                // Captura todos los parámetros de la solicitud actual para pasarlos a la ruta de exportación
                                $exportParams = request()->all();
                            @endphp
                            <a href="{{ route('garments.export', $exportParams) }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition shadow-md sm:ml-auto">
                                <i class="fas fa-file-excel"></i> Exportar a Excel
                            </a>
                        </div>
                    </form>


                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
                        @if ($garments->isEmpty())
                            <p class="p-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron lotes de prendas con los filtros aplicados.
                            </p>
                        @else
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        @foreach (['PV / Talla / Cant.', 'Cliente / Color', 'Línea', 'Motivo', 'Entrada', 'Estado', 'Acciones'] as $col)
                                            <th
                                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                                {{ $col }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($garments as $garment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                                            {{-- PV / TALLA / CANTIDAD --}}
                                            <td class="px-4 py-3 font-bold">
                                                <span
                                                    class="text-indigo-600 dark:text-indigo-400 text-lg">{{ $garment->pv }}</span>
                                                <span class="text-gray-500 dark:text-gray-400"> ({{ $garment->size }} /
                                                    x{{ $garment->quantity }})</span>
                                                @if ($garment->audit_level === 'urgente')
                                                    <span
                                                        class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-600 text-white">
                                                        URGENTE
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- CLIENTE --}}
                                            <td class="px-4 py-3">
                                                <span class="font-semibold">{{ $garment->client->name }}</span><br>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400">{{ $garment->color }}</span>
                                            </td>

                                            {{-- LÍNEA --}}
                                            <td class="px-4 py-3">
                                                {{ $garment->stitchingLine->name }}
                                            </td>

                                            {{-- MOTIVO --}}
                                            <td class="px-4 py-3">
                                                {{ $garment->motive->name }}
                                                <span
                                                    class="ml-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ ucfirst($garment->motive->type) }}
                                                </span>
                                            </td>

                                            {{-- FECHA --}}
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $garment->delivery_in_date->format('d/M/Y H:i') }}
                                            </td>

                                            {{-- ESTADO --}}
                                            <td class="px-4 py-3">
                                                @if ($garment->status === 'pendiente')
                                                    <span
                                                        class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        PENDIENTE
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        ENTREGADO
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- ACCIONES --}}
                                            <td class="px-4 py-3 flex gap-3 items-center">
                                                <a href="{{ route('garments.show', $garment) }}"
                                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    Ver
                                                </a>

                                                @if ($garment->status === 'pendiente')
                                                    {{-- Opción para ENTREGAR (Solo si está pendiente) --}}
                                                    <a href="{{ route('garments.edit', $garment) }}"
                                                        class="text-green-600 dark:text-green-400 hover:underline font-semibold">
                                                        Entregar
                                                    </a>
                                                @elseif($garment->status === 'entregado')
                                                    {{-- Opción para ELIMINAR (Solo si está entregado) --}}
                                                    <button x-data=""
                                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-garment-deletion-{{ $garment->id }}')"
                                                        class="text-red-600 dark:text-red-400 hover:underline text-sm">
                                                        Eliminar
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-sm">Cerrado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="mt-6">
                        {{ $garments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE ELIMINACIÓN (Para cada prenda) (Mantener sin cambios) --}}
    @foreach ($garments as $garment)
        @if ($garment->status === 'entregado')
            <x-modal name="confirm-garment-deletion-{{ $garment->id }}" focusable>
                <form method="post" action="{{ route('garments.destroy', $garment) }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        ¿Estás seguro de que quieres eliminar este lote?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Estás a punto de eliminar el lote PV **{{ $garment->pv }}** (Talla: {{ $garment->size }},
                        Cant.: {{ $garment->quantity }}) de la base de datos.
                        Esta acción es **irreversible**.
                    </p>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Cancelar
                        </x-secondary-button>

                        <x-danger-button class="ms-3">
                            Eliminar Lote
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        @endif
    @endforeach
</x-app-layout>
