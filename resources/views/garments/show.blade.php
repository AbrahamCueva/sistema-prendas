<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalle de Prenda PV:
            <span class="text-indigo-600 dark:text-indigo-400">{{ $garment->pv }}</span>
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900 shadow-xl sm:rounded-2xl border dark:border-gray-700 overflow-hidden">

                {{-- ESTADO --}}
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4
                    @if($garment->status === 'pendiente')
                        bg-yellow-100 dark:bg-yellow-900
                    @else
                        bg-green-100 dark:bg-green-900
                    @endif">

                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Estado Actual</p>

                        @if($garment->status === 'pendiente')
                            <h3 class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">
                                PENDIENTE DE ENTREGA
                            </h3>
                        @else
                            <h3 class="text-2xl font-bold text-green-800 dark:text-green-200">
                                ENTREGADO
                            </h3>
                        @endif
                    </div>

                    @if($garment->status === 'pendiente')
                        <a href="{{ route('garments.edit', $garment) }}"
                           class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                            Registrar Entrega
                        </a>
                    @else
                        <span class="italic text-gray-700 dark:text-gray-300">
                            Proceso de devolución completado
                        </span>
                    @endif
                </div>

                {{-- DATOS GENERALES --}}
                <div class="p-6 space-y-6">
                    <h4 class="text-lg font-bold border-b dark:border-gray-700 pb-2 text-gray-700 dark:text-gray-300">
                        Información General
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <p class="text-sm text-gray-500">Cliente (Marca)</p>
                            <p class="font-bold text-lg">{{ $garment->client->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Color</p>
                            <p class="text-base">{{ $garment->color }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Línea Responsable</p>
                            <p class="text-base">{{ $garment->stitchingLine->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Prioridad</p>
                            @if($garment->is_audit)
                                <span class="px-3 py-1 text-sm rounded-full bg-red-600 text-white font-semibold">
                                    AUDITORÍA (URGENTE)
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm rounded-full bg-gray-200 dark:bg-gray-700">
                                    Normal
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- MOTIVO --}}
                <div class="p-6 border-t dark:border-gray-800 space-y-4">
                    <h4 class="text-lg font-bold border-b dark:border-gray-700 pb-2 text-gray-700 dark:text-gray-300">
                        Motivo del Arreglo
                    </h4>

                    <div>
                        <p class="text-sm text-gray-500">Tipo de Proceso</p>
                        <p class="font-medium">{{ ucfirst($garment->motive->type) }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Descripción</p>
                        <p class="italic">{{ $garment->motive->name }}</p>
                    </div>
                </div>

                {{-- ENTRADA --}}
                <div class="p-6 border-t dark:border-gray-800 space-y-4 bg-gray-50 dark:bg-gray-800">
                    <h4 class="text-lg font-bold border-b dark:border-gray-700 pb-2 text-indigo-600 dark:text-indigo-400">
                        Registro de Entrada
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <p class="text-sm text-gray-500">Fecha y Hora de Entrada</p>
                            <p class="font-medium">
                                {{ $garment->delivery_in_date->format('d/M/Y H:i:s') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Persona que Entregó</p>
                            <p class="font-medium">
                                {{ $garment->delivered_by }}
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Registrado por</p>
                            <p class="font-medium">
                                {{ $garment->registeredByUser->name }}
                            </p>
                        </div>

                    </div>
                </div>

                {{-- SALIDA --}}
                @if($garment->status === 'entregado')
                    <div class="p-6 border-t dark:border-gray-800 space-y-4 bg-green-50 dark:bg-green-900">
                        <h4 class="text-lg font-bold border-b dark:border-gray-700 pb-2 text-green-700 dark:text-green-300">
                            Registro de Devolución
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <p class="text-sm text-gray-500">Fecha y Hora de Devolución</p>
                                <p class="font-bold">
                                    {{ $garment->delivery_out_date->format('d/M/Y H:i:s') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Persona que Recibió</p>
                                <p class="font-medium">
                                    {{ $garment->received_by }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500">Entregado por</p>
                                <p class="font-medium">
                                    {{ $garment->deliveredByUser->name }}
                                </p>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="p-6 border-t dark:border-gray-800 bg-yellow-50 dark:bg-yellow-900 text-center italic text-gray-700 dark:text-gray-300">
                        Aún no hay registro de devolución para esta prenda.
                    </div>
                @endif

                {{-- VOLVER --}}
                <div class="p-6 border-t dark:border-gray-800 flex justify-start">
                    <a href="{{ route('garments.index') }}"
                       class="px-5 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition font-medium">
                        ← Volver al Listado
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
