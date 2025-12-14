<x-app-layout>
    <x-slot name="title">
        {{ __('Detalle de Lote') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
            <i class="fas fa-file-alt text-indigo-500"></i>
            {{ __('Detalle de Lote PV:') }}
            <span class="text-indigo-600 dark:text-indigo-400 text-3xl">{{ $garment->pv }}</span>
        </h2>
    </x-slot>
    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow-2xl sm:rounded-2xl border dark:border-gray-700 overflow-hidden">
                @php
                    $status = $garment->status;
                    $quantity_in = $garment->quantity_in;
                    $quantity_out = $garment->quantity_out;
                    $quantity_pending = $quantity_in - $quantity_out;
                    $status_bg = 'bg-gray-100 dark:bg-gray-900/40';
                    $status_text = 'text-gray-800 dark:text-gray-200';
                    $status_icon = 'fas fa-info-circle';
                    $status_label = strtoupper($status);
                    if ($status === 'entregado') {
                        $status_bg = 'bg-green-100 dark:bg-green-900/40';
                        $status_text = 'text-green-800 dark:text-green-400';
                        $status_icon = 'fas fa-check-circle';
                        $status_label = 'COMPLETADO';
                    } elseif ($status === 'en_proceso') {
                        $status_bg = 'bg-blue-100 dark:bg-blue-900/40';
                        $status_text = 'text-blue-800 dark:text-blue-400';
                        $status_icon = 'fas fa-sync-alt';
                        $status_label = 'EN PROCESO (PARCIAL)';
                    } elseif ($status === 'pendiente') {
                        $status_bg = 'bg-yellow-100 dark:bg-yellow-900/40';
                        $status_text = 'text-yellow-800 dark:text-yellow-400';
                        $status_icon = 'fas fa-exclamation-triangle';
                        $status_label = 'PENDIENTE DE ENTREGA';
                    }
                @endphp
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4 {{ $status_bg }}">
                    <div class="flex items-center gap-3">
                        <i class="{{ $status_icon }} text-3xl {{ $status_text }}"></i>
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Estado Actual del Lote</p>
                            <h3 class="text-2xl font-bold {{ $status_text }}">
                                {{ $status_label }}
                            </h3>
                        </div>
                    </div>
                    @if($quantity_pending > 0)
                        <a href="{{ route('garments.edit', $garment) }}"
                           class="flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-truck"></i> Registrar Entrega
                        </a>
                    @else
                        <span class="italic text-gray-700 dark:text-gray-300">
                            Proceso de devolución completado
                        </span>
                    @endif
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    <div class="p-6 space-y-6">
                        <h4 class="text-lg font-bold pb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-500"></i> Información General del Lote
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-ruler-vertical"></i> Talla</p>
                                <p class="font-bold text-xl dark:text-white">{{ $garment->size }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-boxes"></i> Cantidad Total (Entrada)</p>
                                <p class="font-bold text-xl dark:text-white">{{ $quantity_in }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-sign-out-alt"></i> Cantidad Entregada (Salida)</p>
                                <p class="font-bold text-xl text-green-500">{{ $quantity_out }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-clock"></i> Cantidad Pendiente</p>
                                <p class="font-bold text-xl text-red-500">{{ $quantity_pending }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-palette"></i> Color</p>
                                <p class="text-base font-medium dark:text-gray-200">{{ $garment->color }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-tachometer-alt"></i> Prioridad</p>
                                @if($garment->audit_level === 'urgente')
                                    <span class="mt-1 px-3 py-1 text-sm rounded-full bg-red-600 text-white font-semibold shadow-md">
                                        URGENTE
                                    </span>
                                @else
                                    <span class="mt-1 px-3 py-1 text-sm rounded-full bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        Normal
                                    </span>
                                @endif
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-tag"></i> Cliente (Marca)</p>
                                <p class="font-semibold text-lg text-indigo-600 dark:text-indigo-400">{{ $garment->client->name }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-cogs"></i> Línea Responsable</p>
                                <p class="text-lg font-medium dark:text-gray-200">{{ $garment->stitchingLine->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4 bg-gray-50 dark:bg-gray-800">
                        <h4 class="text-lg font-bold pb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fas fa-wrench text-amber-500"></i> Detalle del Motivo y Evidencia
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <h4 class="text-lg font-bold pb-2 text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                            <i class="fas fa-sign-in-alt"></i> Registro de Entrada
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha y Hora de Entrada</p>
                                <p class="font-semibold text-base dark:text-gray-200">
                                    {{ $garment->delivery_in_date->format('d/M/Y H:i:s') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad Ingresada Inicialmente</p>
                                <p class="font-semibold text-base dark:text-gray-200">
                                    {{ $quantity_in }} prendas
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Entregado por (Taller/Proveedor)</p>
                                <p class="font-medium text-base dark:text-gray-200">
                                    {{ $garment->delivered_by }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Registrado en el Sistema por</p>
                                <p class="font-medium text-base dark:text-gray-200">
                                    {{ $garment->registeredByUser->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @if($quantity_out > 0)
                        <div class="p-6 space-y-4 bg-green-50 dark:bg-green-900/50">
                            <h4 class="text-lg font-bold pb-2 text-green-700 dark:text-green-300 flex items-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Último Registro de Devolución
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha y Hora de la Última Devolución</p>
                                    <p class="font-bold text-base dark:text-white">
                                        {{ $garment->delivery_out_date->format('d/M/Y H:i:s') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad Total Devuelta (Acumulado)</p>
                                    <p class="font-bold text-base dark:text-white">
                                        {{ $quantity_out }} prendas
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Última Persona que Recibió</p>
                                    <p class="font-medium text-base dark:text-gray-200">
                                        {{ $garment->received_by }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Entregado del Sistema por</p>
                                    <p class="font-medium text-base dark:text-gray-200">
                                        {{ $garment->deliveredByUser->name }}
                                    </p>
                                </div>
                            </div>
                            @if ($status === 'en_proceso')
                                <div class="mt-4 p-3 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 font-semibold">
                                    <i class="fas fa-exclamation-circle"></i> ¡Aún quedan **{{ $quantity_pending }}** prendas por entregar!
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-6 bg-yellow-50 dark:bg-yellow-900/50 text-center italic text-sm text-gray-700 dark:text-yellow-300">
                            <i class="fas fa-clock"></i> Aún no hay registro de devolución (Salida) para este lote.
                        </div>
                    @endif

                </div>
                <div class="p-6 border-t dark:border-gray-800 flex justify-start bg-gray-100 dark:bg-gray-900">
                    <a href="{{ route('garments.index') }}"
                       class="flex items-center gap-2 px-5 py-2 bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg transition font-medium text-gray-800 dark:text-gray-200">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
