<x-app-layout>
    <x-slot name="title">{{ __('Detalle de Auditoría') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Detalle de Actividad') }}
        </h2>
    </x-slot>

    {{-- Usamos el fondo oscuro de la app layout --}}
    <div class="py-12 bg-gray-100 dark:bg-gray-950">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Contenedor principal con estilo de tarjeta dark --}}
            <div class="bg-white dark:bg-gray-900 shadow-xl rounded-xl p-8">

                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                    Evento: {{ ucfirst($activity->description) }}
                </h3>

                {{-- Detalles Generales --}}
                <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                    <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-200 mb-3">Información General</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="font-bold text-gray-600 dark:text-gray-400">Usuario que Ejecutó:</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $activity->causer ? $activity->causer->name : 'Sistema/Desconocido' }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-600 dark:text-gray-400">Fecha y Hora:</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $activity->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-600 dark:text-gray-400">Modelo Afectado:</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ Str::afterLast($activity->subject_type, '\\') }} (ID: {{ $activity->subject_id }})</p>
                        </div>
                        <div>
                            <p class="font-bold text-gray-600 dark:text-gray-400">Tipo de Evento:</p>
                            <p class="font-semibold {{ $activity->event == 'updated' ? 'text-indigo-500' : ($activity->event == 'created' ? 'text-green-500' : 'text-red-500') }}">
                                {{ ucfirst($activity->event) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Comparación de Propiedades (Tabla de 3 columnas para Diff) --}}
                @if ($activity->changes && !empty($activity->changes['attributes']))
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-8 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Propiedades Modificadas
                    </h4>

                    <div class="overflow-hidden rounded-lg border dark:border-gray-700 shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            {{-- Cabecera de la tabla de comparación --}}
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/3">Campo</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider w-1/3">Valor Anterior</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider w-1/3">Nuevo Valor</th>
                                </tr>
                            </thead>
                            {{-- Cuerpo de la tabla de comparación --}}
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($activity->changes['attributes'] as $key => $newValue)
                                    @php
                                        // Obtener el valor antiguo para la comparación, si no existe (creación), se marca
                                        $oldValue = $activity->changes['old'][$key] ?? '— NUEVO REGISTRO —';

                                        // Formateo de valor si es un timestamp (para que se vea mejor que el Z)
                                        $isDate = in_array($key, ['created_at', 'updated_at', 'delivery_out_date', 'delivery_in_date']) || is_numeric(strtotime($newValue));

                                        $displayNewValue = $isDate ? (is_numeric(strtotime($newValue)) ? \Carbon\Carbon::parse($newValue)->format('Y-m-d H:i:s') : $newValue) : $newValue;
                                        $displayOldValue = $isDate && $oldValue != '— NUEVO REGISTRO —' ? (\Carbon\Carbon::parse($oldValue)->format('Y-m-d H:i:s') ?? $oldValue) : $oldValue;

                                        // Mostrar el cambio como '---' si no existía antes
                                        if ($oldValue === '— NUEVO REGISTRO —') {
                                            $displayOldValue = '---';
                                        }

                                    @endphp

                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $key }}</td>

                                        {{-- Valor Anterior (rojo y tachado) --}}
                                        <td class="px-6 py-3 text-sm text-red-500 dark:text-red-400 {{ $oldValue != '— NUEVO REGISTRO —' ? 'line-through' : '' }}">
                                            {{ $displayOldValue }}
                                        </td>

                                        {{-- Nuevo Valor (verde) --}}
                                        <td class="px-6 py-3 text-sm text-green-600 dark:text-green-500">
                                            {{ $displayNewValue }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-sm text-gray-500 dark:text-gray-400 mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                        No hay cambios específicos de atributos registrados para este evento.
                        Esto suele ocurrir en eventos de creación donde todos los valores son "nuevos".
                    </div>
                @endif

                <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 text-right">
                    <a href="{{ route('audit.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                        Volver al Historial
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
