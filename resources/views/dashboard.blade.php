<x-app-layout>
    <x-slot name="title">
        {{ __('Panel de Control') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Control (Métricas de Prendas)') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Usaremos 5 columnas en lugar de 6 para un diseño más limpio --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                
                {{-- 1. Total Prendas Ingresadas (Nuevo - Suma de quantity_in) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-indigo-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-box-open text-3xl text-indigo-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total **Prendas** Ingresadas</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalGarmentsIn) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- 2. Prendas Pendientes (Nuevo - Suma de quantity_in en lotes pendientes/en proceso) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-yellow-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-3xl text-yellow-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">**Prendas** Pendientes</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingGarmentsQuantity) }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('garments.index', ['status' => 'pendiente']) }}"
                        class="mt-3 block text-xs text-yellow-600 hover:text-yellow-800 font-semibold dark:text-yellow-400 dark:hover:text-yellow-300">Ver
                        Detalle &rarr;</a>
                </div>

                {{-- 3. Prendas Entregadas (Nuevo - Suma de quantity_in en lotes entregados) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-green-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-handshake text-3xl text-green-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">**Prendas** Entregadas</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($deliveredGarmentsQuantity) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- 4. Tasa de Entrega (Nuevo - Basado en Prendas) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-sky-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-3xl text-sky-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tasa de Entrega</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $deliveryRate }}%</h3>
                        </div>
                    </div>
                </div>
                
                {{-- 5. Tasa de Auditoría / Defecto (Nuevo - Basado en Prendas con is_audit=true) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-red-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tasa de Auditoría/Defecto</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $auditRate }}%</h3>
                        </div>
                    </div>
                </div>

                {{-- Puedes agregar un sexto widget si lo deseas, usando las variables que ya tienes, por ejemplo: --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-red-500 transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center">
                            <i class="fas fa-fire text-3xl text-red-500 mr-4"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lotes de Auditoría (Urgentes)</p>
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $urgentGarmentsLots }}</h3>
                            </div>
                        </div>
                        @if ($urgentGarmentsLots > 0)
                            <a href="{{ route('garments.index', ['urgent' => 1]) }}"
                                class="mt-3 block text-xs text-red-600 hover:text-red-800 font-semibold dark:text-red-400 dark:hover:text-red-300">¡Atender
                                ahora! &rarr;</a>
                        @endif
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-purple-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-tshirt text-3xl text-purple-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Lotes Registrados</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalLots }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Mantenemos los gráficos y la tabla de lotes antiguos --}}
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b dark:border-gray-700 pb-2">
                        Distribución por Línea de Costura/Servicio 
                    </h3>
                    <div class="relative h-80">
                        <canvas id="lineChart"></canvas>
                    </div>
                    @if (empty($lineCounts))
                        <p class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">No hay datos de lotes
                            registrados aún para mostrar la distribución.</p>
                    @endif
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b dark:border-gray-700 pb-2">
                        Distribución por Motivo de Arreglo 
                    </h3>
                    <div class="relative h-80">
                        <canvas id="motiveChart"></canvas>
                    </div>
                    @if (empty($motiveCounts))
                        <p class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">No hay datos de motivos
                            registrados para la distribución.</p>
                    @endif
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b dark:border-gray-700 pb-2 flex justify-between items-center">
                        <span>🚨 Lotes Pendientes más Antiguos (Top 5)</span>
                        <a href="{{ route('garments.index', ['status' => 'pendiente']) }}"
                            class="text-sm text-indigo-500 hover:text-indigo-400">Ver todos &rarr;</a>
                    </h3>

                    @if ($latestPendingGarments->isEmpty())
                        <p class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">¡Excelente! No hay lotes
                            pendientes o todos son recientes.</p>
                    @else
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                            Antigüedad</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                            PV / Cant. **Prendas**</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                            Cliente</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                            Motivo</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                            Línea de Servicio</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($latestPendingGarments as $garment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <td class="px-4 py-2 text-xs font-bold text-red-600 dark:text-red-400">
                                                @php
                                                    $daysOld = $garment->created_at->diffInDays(now());
                                                    $ageText =
                                                        $daysOld == 0
                                                            ? 'Hoy'
                                                            : ($daysOld == 1
                                                                ? '1 día'
                                                                : $daysOld . ' días');
                                                    $ageColor =
                                                        $daysOld >= 7
                                                            ? 'text-red-600 dark:text-red-400'
                                                            : ($daysOld >= 3
                                                                ? 'text-yellow-600 dark:text-yellow-400'
                                                                : 'text-green-600 dark:text-green-400');
                                                @endphp
                                                <span class="{{ $ageColor }} font-bold">{{ $ageText }}</span>
                                            </td>
                                            <td
                                                class="px-4 py-2 text-sm font-semibold text-blue-600 dark:text-blue-400">
                                                {{ $garment->pv }} (x{{ number_format($garment->quantity_in) }})
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $garment->client->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-2 text-xs">
                                                <span
                                                    class="font-medium text-gray-700 dark:text-gray-300">{{ $garment->motive->name ?? 'N/A' }}</span>
                                            </td>
                                            {{-- Agregamos la línea de servicio para mayor contexto --}}
                                            <td class="px-4 py-2 text-xs">
                                                <span
                                                    class="font-medium text-gray-700 dark:text-gray-300">{{ $garment->stitchingLine->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-4 py-2 text-center text-xs">
                                                <a href="{{ route('garments.show', $garment) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Ver
                                                    Detalle</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p class="text-gray-700 font-semibold dark:text-gray-200">¡Bienvenido de vuelta,
                    {{ Auth::user()->name }}!</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Utiliza el menú superior para acceder al **Registro
                    de Prendas** o gestionar los datos maestros (Clientes, Líneas, Motivos).</p>
            </div>
        </div>
    </div>
    {{-- Mantenemos los scripts de Chart.js intactos --}}
    @push('scripts')
        <script>
            // ... (El código de Chart.js es el mismo, solo que ahora usa las nuevas variables del controller, pero los nombres de las colecciones de gráficos no cambiaron) ...
            document.addEventListener('DOMContentLoaded', function() {
                const lineLabels = @json($lineLabels);
                const lineCounts = @json($lineCounts);
                const chartTextColor = window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'rgba(255, 255, 255, 0.8)' : '#4b5563';
                const chartGridColor = window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const ctx = document.getElementById('lineChart');
                if (ctx && lineCounts.length > 0) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: lineLabels,
                            datasets: [{
                                label: '# de Lotes Ingresados',
                                data: lineCounts,
                                backgroundColor: [
                                    '#4f46e5', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6',
                                    '#06b6d4', '#f97316'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: chartGridColor,
                                    },
                                    ticks: {
                                        color: chartTextColor
                                    }
                                },
                                x: {
                                    grid: {
                                        color: chartGridColor,
                                    },
                                    ticks: {
                                        color: chartTextColor
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: chartTextColor,
                                    }
                                }
                            }
                        }
                    });
                }
                const motiveLabels = @json($motiveLabels);
                const motiveCounts = @json($motiveCounts);
                const ctxMotive = document.getElementById('motiveChart');
                if (ctxMotive && motiveCounts.length > 0) {
                    new Chart(ctxMotive, {
                        type: 'doughnut',
                        data: {
                            labels: motiveLabels,
                            datasets: [{
                                label: '# de Prendas',
                                data: motiveCounts,
                                backgroundColor: [
                                    '#f59e0b', '#4f46e5', '#ef4444', '#10b981', '#8b5cf6',
                                    '#06b6d4'
                                ],
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        color: chartTextColor
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>