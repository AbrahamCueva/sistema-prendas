<x-app-layout>
    <x-slot name="title">
        {{ __('Registro y Seguimiento de Prendas') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registro y Seguimiento de Prendas') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    
                    {{-- HEADER Y BOTÓN DE REGISTRO --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-semibold">Listado de Lotes de Prendas</h3>
                        <a href="{{ route('garments.create') }}"
                            class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow">
                            <i class="fas fa-plus"></i> Registrar ENTRADA
                        </a>
                    </div>
                    
                    {{-- MENSAJES DE SESIÓN --}}
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
                    
                    {{-- FORMULARIO DE FILTROS --}}
                    <form method="GET" action="{{ route('garments.index') }}"
                        class="mb-6 p-4 border dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-inner">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                            <div>
                                <label for="search_pv"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">PV</label>
                                <input type="text" name="search_pv" id="search_pv" value="{{ request('search_pv') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="client_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
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
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
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
                            <div>
                                <label for="date_from"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha
                                    Desde</label>
                                <input type="date" name="date_from" id="date_from"
                                    value="{{ request('date_from') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="date_to"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha
                                    Hasta</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-md font-medium transition shadow-md">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('garments.index') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition shadow-md">
                                <i class="fas fa-sync-alt"></i> Limpiar Filtros
                            </a>
                            @php
                                $exportParams = request()->all();
                            @endphp
                            <a href="{{ route('garments.export', $exportParams) }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition shadow-md sm:ml-auto">
                                <i class="fas fa-file-excel"></i> Exportar a Excel
                            </a>
                        </div>
                    </form>

                    {{-- TABLA DE RESULTADOS --}}
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
                                            <td class="px-4 py-3 font-bold">
                                                <span
                                                    class="text-indigo-600 dark:text-indigo-400 text-lg">{{ $garment->pv }}</span>
                                                <span class="text-gray-500 dark:text-gray-400"> (
                                                    {{-- Muestra las tallas. Si es un array (por el cast), las une. Si es string, la muestra. --}}
                                                    {{ is_array($garment->sizes) ? implode(', ', $garment->sizes) : $garment->sizes }} 
                                                    /
                                                    x{{ $garment->quantity_in }}
                                                    @if ($garment->quantity_out > 0 && $garment->quantity_out != $garment->quantity_in)
                                                        <span class="text-green-600 dark:text-green-400"> | Entregado:
                                                            {{ $garment->quantity_out }}</span>
                                                    @endif
                                                    @php
                                                        $remaining = $garment->quantity_in - $garment->quantity_out;
                                                    @endphp
                                                    @if ($garment->calculated_status === 'pendiente' && $remaining > 0)
                                                        <span class="text-orange-600 dark:text-orange-400"> | Pendiente:
                                                            {{ $remaining }}</span>
                                                    @endif
                                                    )
                                                </span>
                                                @if ($garment->audit_level === 'urgente')
                                                    <span
                                                        class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-600 text-white">
                                                        URGENTE
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-semibold">{{ $garment->client->name }}</span><br>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400">{{ $garment->color }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $garment->stitchingLine->name }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $garment->motive->name }}
                                                <span
                                                    class="ml-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ ucfirst($garment->motive->type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $garment->delivery_in_date->format('d/M/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $status = $garment->calculated_status;
                                                @endphp
                                                @if ($status === 'pendiente')
                                                    <span
                                                        class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        PENDIENTE
                                                    </span>
                                                @elseif ($status === 'entregado')
                                                    <span
                                                        class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        ENTREGADO
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        CERRADO
                                                    </span>
                                                @endif
                                            </td>
                                            
                                            {{-- ACCIONES CON MODAL CORREGIDO --}}
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex space-x-2">
                                                    {{-- Ver Detalle --}}
                                                    <a href="{{ route('garments.show', $garment) }}"
                                                        class="p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition"
                                                        title="Ver Detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    {{-- Añadir Stock (si está pendiente) --}}
                                                    @if ($garment->calculated_status === 'pendiente')
                                                        <a href="{{ route('garments.add-stock.form', $garment) }}"
                                                            class="p-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition"
                                                            title="Añadir más Stock">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Registrar Devolución/Entrega (si aún hay pendiente) --}}
                                                    @if ($garment->quantity_in > $garment->quantity_out)
                                                        <a href="{{ route('garments.edit', $garment) }}"
                                                            class="p-2 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 transition"
                                                            title="Registrar Devolución/Entrega">
                                                            <i class="fas fa-truck"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Eliminar (solo si está entregado) - DISPARA MODAL --}}
                                                    @if ($garment->calculated_status === 'entregado')
                                                        <button type="button" 
                                                            x-data=""
                                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-garment-deletion-{{ $garment->id }}')"
                                                            class="p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition"
                                                            title="Eliminar Lote">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endif

                                                </div>
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
    
    {{-- DEFINICIÓN DE LOS MODALES DE ELIMINACIÓN --}}
    @foreach ($garments as $garment)
        @if ($garment->calculated_status === 'entregado')
            <x-modal name="confirm-garment-deletion-{{ $garment->id }}" focusable>
                <form method="post" action="{{ route('garments.destroy', $garment) }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        ¿Estás seguro de que quieres eliminar este lote?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Estás a punto de eliminar el lote PV **{{ $garment->pv }}** (Tallas: 
                        {{ is_array($garment->sizes) ? implode(', ', $garment->sizes) : $garment->sizes }},
                        Cant.: {{ $garment->quantity_in }}) de la base de datos.
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