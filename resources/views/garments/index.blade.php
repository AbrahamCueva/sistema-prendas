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

                    {{-- HEADER --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-semibold">Listado de Lotes de Prendas</h3>

                        <a href="{{ route('garments.create') }}"
                            class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow">
                            Registrar ENTRADA
                        </a>
                    </div>

                    {{-- ALERTAS --}}
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

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
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
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="mt-6">
                        {{ $garments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE ELIMINACIÓN (Para cada prenda) --}}
    @foreach ($garments as $garment)
        {{-- CAMBIAR DE 'pendiente' A 'entregado' --}}
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
