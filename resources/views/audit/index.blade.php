<x-app-layout>
    <x-slot name="title">{{ __('Registro de Auditoría') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Trazabilidad de Cambios') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                        Historial de Actividad del Sistema
                    </h3>

                    {{-- FORMULARIO DE FILTROS Y BÚSQUEDA (GET) --}}
                    <form method="GET" action="{{ route('audit.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 items-end">

                            {{-- BÚSQUEDA GLOBAL --}}
                            <div class="col-span-2 md:col-span-1 lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar (Usuario, Acción, Modelo)</label>
                                <input type="text" name="search" id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Buscar actividad..."
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            {{-- FILTRO POR EVENTO --}}
                            <div>
                                <label for="event" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Acción</label>
                                <select name="event" id="event"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">— Todos los eventos —</option>
                                    <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Creación (➕)</option>
                                    <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Actualización (✏️)</option>
                                    <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Eliminación (🗑️)</option>
                                </select>
                            </div>

                            {{-- FILTRO POR FECHA (Mínima) --}}
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Desde</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            {{-- FILTRO POR FECHA (Máxima) --}}
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Hasta</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            {{-- BOTONES DE ACCIÓN --}}
                            <div class="col-span-1 flex space-x-2">
                                <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition">
                                    Filtrar
                                </button>

                                {{-- Botón de Limpiar Filtros --}}
                                @if (request()->hasAny(['search', 'event', 'start_date', 'end_date']))
                                    <a href="{{ route('audit.index') }}"
                                        class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                        Limpiar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- TABLA DE RESULTADOS --}}
                    <div class="overflow-x-auto rounded-lg border dark:border-gray-700 shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/12">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/12">
                                        Usuario
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-3/12">
                                        Acción
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-3/12">
                                        Elemento Afectado
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/12">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($activities as $activity)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        {{-- Fecha, Usuario, Acción, Modelo Afectado... (sin cambios aquí) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-semibold">{{ $activity->created_at->format('Y-m-d') }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-500">{{ $activity->created_at->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $activity->causer ? $activity->causer->name : 'Sistema' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            @php
                                                $color = ['created' => 'text-green-600 dark:text-green-400', 'updated' => 'text-indigo-600 dark:text-indigo-400', 'deleted' => 'text-red-600 dark:text-red-400', 'logged in' => 'text-blue-500 dark:text-blue-300'];
                                                $icon = ['created' => '➕', 'updated' => '✏️', 'deleted' => '🗑️', 'logged in' => '🔑'];
                                                $event = $activity->event ?? $activity->description;
                                                $displayColor = $color[$event] ?? 'text-gray-500 dark:text-gray-400';
                                                $displayIcon = $icon[$event] ?? 'ℹ️';
                                            @endphp
                                            <span class="{{ $displayColor }} font-bold">
                                                {{ $displayIcon }} {{ ucfirst($activity->description) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            <div class="font-semibold">{{ Str::afterLast($activity->subject_type, '\\') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-500">ID: {{ $activity->subject_id }}</div>
                                        </td>

                                        {{-- COLUMNA DE ACCIONES AMPLIADA --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                            {{-- Botón VER DETALLES --}}
                                            <a href="{{ route('audit.show', $activity) }}"
                                               class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                                Detalles
                                            </a>

                                            {{-- ¡NUEVO! Botón GENERAR PDF --}}
                                            <a href="{{ route('audit.report', $activity) }}" target="_blank"
                                               class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                                                PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                            <p class="mb-1 text-base">😔</p>
                                            <p>No se encontraron actividades que coincidan con los filtros aplicados.</p>
                                            <p class="text-xs">Intenta limpiar o ajustar los parámetros de búsqueda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6 text-gray-900 dark:text-gray-100">
                        {{ $activities->appends(request()->except('page'))->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
