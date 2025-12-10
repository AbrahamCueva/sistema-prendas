<x-app-layout>
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

                {{-- ESTADO BANNER (Mejorado) --}}
                @php
                    $is_pending = $garment->status === 'pendiente';
                    // Contraste optimizado: colores de fondo más suaves en modo oscuro para que el texto resalte.
                    $status_bg = $is_pending ? 'bg-yellow-100 dark:bg-yellow-900/40' : 'bg-green-100 dark:bg-green-900/40';
                    // Texto con alto contraste
                    $status_text = $is_pending ? 'text-yellow-800 dark:text-yellow-400' : 'text-green-800 dark:text-green-400';
                    $status_label = $is_pending ? 'PENDIENTE DE ENTREGA' : 'ENTREGADO';
                    $status_icon = $is_pending ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
                @endphp
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4 {{ $status_bg }}">

                    <div class="flex items-center gap-3">
                        <i class="{{ $status_icon }} text-3xl {{ $status_text }}"></i>
                        <div>
                            {{-- Ajuste de texto para dark mode --}}
                            <p class="text-sm text-gray-700 dark:text-gray-300">Estado Actual del Lote</p>
                            <h3 class="text-2xl font-bold {{ $status_text }}">
                                {{ $status_label }}
                            </h3>
                        </div>
                    </div>

                    @if($is_pending)
                        <a href="{{ route('garments.edit', $garment) }}"
                           class="flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fas fa-truck"></i> Registrar Entrega
                        </a>
                    @else
                        {{-- Ajuste de texto para dark mode --}}
                        <span class="italic text-gray-700 dark:text-gray-300">
                            Proceso de devolución completado
                        </span>
                    @endif
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-800">

                    {{-- DATOS GENERALES --}}
                    <div class="p-6 space-y-6">
                        {{-- Ajuste de texto para dark mode --}}
                        <h4 class="text-lg font-bold pb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-500"></i> Información General del Lote
                        </h4>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                            {{-- Talla --}}
                            <div>
                                {{-- Etiquetas a gray-400 en dark mode --}}
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-ruler-vertical"></i> Talla</p>
                                {{-- Valores a white o gray-100 en dark mode --}}
                                <p class="font-bold text-xl dark:text-white">{{ $garment->size }}</p>
                            </div>

                            {{-- Cantidad --}}
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-boxes"></i> Cantidad</p>
                                <p class="font-bold text-xl dark:text-white">{{ $garment->quantity }}</p>
                            </div>

                            {{-- Color --}}
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-palette"></i> Color</p>
                                <p class="text-base font-medium dark:text-gray-200">{{ $garment->color }}</p>
                            </div>

                            {{-- Prioridad --}}
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

                            {{-- Cliente --}}
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-tag"></i> Cliente (Marca)</p>
                                <p class="font-semibold text-lg text-indigo-600 dark:text-indigo-400">{{ $garment->client->name }}</p>
                            </div>

                            {{-- Línea --}}
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1"><i class="fas fa-cogs"></i> Línea Responsable</p>
                                <p class="text-lg font-medium dark:text-gray-200">{{ $garment->stitchingLine->name }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- MOTIVO --}}
                    {{-- Usar un fondo ligeramente diferente para separación visual --}}
                    <div class="p-6 space-y-4 bg-gray-50 dark:bg-gray-800">
                        {{-- Ajuste de texto para dark mode --}}
                        <h4 class="text-lg font-bold pb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fas fa-wrench text-amber-500"></i> Detalle del Motivo
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Tipo de Proceso --}}
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tipo de Proceso</p>
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 font-medium">
                                    {{ ucfirst($garment->motive->type) }}
                                </span>
                            </div>

                            {{-- Descripción del Motivo --}}
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Motivo Específico</p>
                                <p class="font-medium text-base dark:text-gray-200">{{ $garment->motive->name }}</p>
                            </div>

                        </div>
                    </div>

                    {{-- REGISTRO DE ENTRADA --}}
                    <div class="p-6 space-y-4">
                        <h4 class="text-lg font-bold pb-2 text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                            <i class="fas fa-sign-in-alt"></i> Registro de Entrada
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Fecha y Hora de Entrada --}}
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fecha y Hora de Entrada</p>
                                <p class="font-semibold text-base dark:text-gray-200">
                                    {{ $garment->delivery_in_date->format('d/M/Y H:i:s') }}
                                </p>
                            </div>

                            {{-- Persona que Entregó --}}
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Entregado por (Taller/Proveedor)</p>
                                <p class="font-medium text-base dark:text-gray-200">
                                    {{ $garment->delivered_by }}
                                </p>
                            </div>

                            {{-- Registrado por --}}
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Registrado en el Sistema por</p>
                                <p class="font-medium text-base dark:text-gray-200">
                                    {{ $garment->registeredByUser->name }}
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- REGISTRO DE SALIDA (DEVOLUCIÓN) --}}
                    @if($garment->status === 'entregado')
                        <div class="p-6 space-y-4 bg-green-50 dark:bg-green-900/50">
                            <h4 class="text-lg font-bold pb-2 text-green-700 dark:text-green-300 flex items-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Registro de Devolución
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- Fecha y Hora de Devolución --}}
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha y Hora de Devolución</p>
                                    <p class="font-bold text-base dark:text-white">
                                        {{ $garment->delivery_out_date->format('d/M/Y H:i:s') }}
                                    </p>
                                </div>

                                {{-- Persona que Recibió --}}
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Recibido por (Taller/Proveedor)</p>
                                    <p class="font-medium text-base dark:text-gray-200">
                                        {{ $garment->received_by }}
                                    </p>
                                </div>

                                {{-- Entregado por --}}
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Entregado del Sistema por</p>
                                    <p class="font-medium text-base dark:text-gray-200">
                                        {{ $garment->deliveredByUser->name }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    @else
                        {{-- Mensaje de pendiente con contraste --}}
                        <div class="p-6 bg-yellow-50 dark:bg-yellow-900/50 text-center italic text-sm text-gray-700 dark:text-yellow-300">
                            <i class="fas fa-clock"></i> Aún no hay registro de devolución (Salida) para este lote.
                        </div>
                    @endif

                </div> {{-- Cierre del div divide-y --}}

                {{-- VOLVER --}}
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
