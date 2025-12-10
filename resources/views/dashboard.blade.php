<x-app-layout>

    {{-- 1. MODIFICADO: Añadido el slot 'title' para el título de la pestaña --}}
    <x-slot name="title">
        {{ __('Panel de Control') }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- CUADRÍCULA 1: Tarjetas de Conteo (4 columnas) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Tarjeta 1: Total de Lotes Registrados --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-indigo-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-tshirt text-3xl text-indigo-500 mr-4"></i>
                        <div>
                            {{-- 2. MODIFICADO: Cambiado a "Total Lotes Registrados" --}}
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Lotes Registrados</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalGarments }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 2: Lotes Pendientes de Entrega --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-yellow-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-3xl text-yellow-500 mr-4"></i>
                        <div>
                            {{-- 2. MODIFICADO: Cambiado a "Lotes Pendientes de Entrega" --}}
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lotes Pendientes de Entrega
                            </p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingGarments }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('garments.index', ['status' => 'pendiente']) }}"
                        class="mt-3 block text-xs text-yellow-600 hover:text-yellow-800 font-semibold dark:text-yellow-400 dark:hover:text-yellow-300">Ver
                        Pendientes &rarr;</a>
                </div>

                {{-- Tarjeta 3: Lotes Entregados --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-green-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-handshake text-3xl text-green-500 mr-4"></i>
                        <div>
                            {{-- 2. MODIFICADO: Cambiado a "Lotes Entregados" --}}
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lotes Entregados</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $deliveredGarments }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 4: Auditoría Urgente --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border-b-4 border-red-500 transition duration-300 hover:shadow-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-fire text-3xl text-red-500 mr-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Auditoría (Urgentes)</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $urgentGarments }}</h3>
                        </div>
                    </div>
                    @if ($urgentGarments > 0)
                        <a href="{{ route('garments.index', ['urgent' => 1]) }}"
                            class="mt-3 block text-xs text-red-600 hover:text-red-800 font-semibold dark:text-red-400 dark:hover:text-red-300">¡Atender
                            ahora! &rarr;</a>
                    @endif
                </div>
            </div>

            {{-- CUADRÍCULA 2: GRÁFICOS (2 columnas) --}}
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Gráfico 1: Distribución de Prendas por Línea --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b dark:border-gray-700 pb-2">
                        Distribución por Línea de Costura/Servicio
                    </h3>
                    {{-- AÑADE UN CONTENEDOR CON ALTURA FIJA (ej. h-80) --}}
                    <div class="relative h-80">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                {{-- Gráfico 2: Distribución por Motivo de Arreglo --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b dark:border-gray-700 pb-2">
                        Distribución por Motivo de Arreglo
                    </h3>
                    {{-- AÑADE UN CONTENEDOR CON ALTURA FIJA --}}
                    <div class="relative h-80">
                        <canvas id="motiveChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- Bloque de Bienvenida Inferior --}}
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p class="text-gray-700 font-semibold dark:text-gray-200">¡Bienvenido de vuelta,
                    {{ Auth::user()->name }}!</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Utiliza el menú superior para acceder al **Registro
                    de Prendas** o gestionar los datos maestros (Clientes, Líneas, Motivos).</p>
            </div>

        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lineLabels = @json($lineLabels);
                const lineCounts = @json($lineCounts);

                // Define el color del texto de la gráfica (para modo oscuro)
                const chartTextColor = window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'rgba(255, 255, 255, 0.8)' : '#4b5563';
                const chartGridColor = window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

                const ctx = document.getElementById('lineChart');

                if (ctx && lineCounts.length > 0) { // Solo dibuja si hay datos
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
                } else if (ctx) {
                    // Muestra un mensaje si no hay datos para dibujar
                    ctx.parentElement.innerHTML =
                        '<p class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">No hay datos de lotes registrados aún para mostrar la distribución.</p>';
                }

                const motiveLabels = @json($motiveLabels);
                const motiveCounts = @json($motiveCounts);
                const ctxMotive = document.getElementById('motiveChart');

                if (ctxMotive && motiveCounts.length > 0) {
                    new Chart(ctxMotive, {
                        type: 'doughnut', // Usamos gráfico de dona/circular
                        data: {
                            labels: motiveLabels,
                            datasets: [{
                                label: '# de Prendas',
                                data: motiveCounts,
                                backgroundColor: [
                                    '#f59e0b', '#4f46e5', '#ef4444', '#10b981', '#8b5cf6',
                                    '#06b6d4' // Usamos la misma paleta
                                ],
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right', // Colocamos la leyenda a la derecha
                                    labels: {
                                        color: chartTextColor
                                    }
                                }
                            }
                        }
                    });
                } else if (ctxMotive) {
                    ctxMotive.parentElement.innerHTML =
                        '<p class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">No hay datos de motivos registrados para la distribución.</p>';
                }
            });
        </script>
    @endpush
</x-app-layout>
