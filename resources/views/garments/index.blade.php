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
                        <h3 class="text-xl font-semibold">Listado de Prendas</h3>

                        <a href="{{ route('garments.create') }}"
                           class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow">
                            Registrar ENTRADA
                        </a>
                    </div>

                    {{-- ALERTAS --}}
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-800 dark:text-green-200 p-4 mb-4 rounded">
                            <p class="font-bold">Éxito</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-800 dark:text-red-200 p-4 mb-4 rounded">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    @foreach (['PV','Cliente / Color','Línea','Motivo','Entrada','Estado','Acciones'] as $col)
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                            {{ $col }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($garments as $garment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                                        {{-- PV --}}
                                        <td class="px-4 py-3 font-bold">
                                            {{ $garment->pv }}
                                            @if($garment->is_audit)
                                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-600 text-white">
                                                    URGENTE
                                                </span>
                                            @endif
                                        </td>

                                        {{-- CLIENTE --}}
                                        <td class="px-4 py-3">
                                            <span class="font-semibold">{{ $garment->client->name }}</span><br>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $garment->color }}</span>
                                        </td>

                                        {{-- LÍNEA --}}
                                        <td class="px-4 py-3">
                                            {{ $garment->stitchingLine->name }}
                                        </td>

                                        {{-- MOTIVO --}}
                                        <td class="px-4 py-3">
                                            {{ $garment->motive->name }}
                                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ ucfirst($garment->motive->type) }}
                                            </span>
                                        </td>

                                        {{-- FECHA --}}
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $garment->delivery_in_date->format('d/M/Y H:i') }}
                                        </td>

                                        {{-- ESTADO --}}
                                        <td class="px-4 py-3">
                                            @if($garment->status === 'pendiente')
                                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    PENDIENTE
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
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

                                            @if($garment->status === 'pendiente')
                                                <a href="{{ route('garments.edit', $garment) }}"
                                                   class="text-green-600 dark:text-green-400 hover:underline font-semibold">
                                                    Entregar
                                                </a>
                                            @else
                                                <span class="text-gray-400">Entregado</span>
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
</x-app-layout>
