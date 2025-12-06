<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registro de Entrada de Prenda') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-200">

                    <form method="POST" action="{{ route('garments.store') }}">
                        @csrf

                        <h3 class="text-xl font-semibold mb-6 text-indigo-600 dark:text-indigo-400 border-b dark:border-gray-700 pb-2">
                            Datos de la Prenda
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- PV --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1">PV (Código Único)</label>
                                <input id="pv" type="text" name="pv" value="{{ old('pv', $randomPV) }}" required maxlength="5"
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('pv') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Color --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1">Color de la Prenda</label>
                                <input id="color" type="text" name="color" value="{{ old('color') }}" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('color') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Cliente --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1">Cliente (Marca)</label>
                                <select name="client_id" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
                                    <option value="">Selecciona una marca</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Línea --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1">Línea que la Hizo</label>
                                <select name="stitching_line_id" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
                                    <option value="">Selecciona una línea</option>
                                    @foreach($lines as $line)
                                        <option value="{{ $line->id }}" {{ old('stitching_line_id') == $line->id ? 'selected' : '' }}>
                                            {{ $line->name }} {{ $line->is_external_service ? '(Servicio Externo)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('stitching_line_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Motivo --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1">Motivo de Arreglo</label>
                                <select name="motive_id" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
                                    <option value="">Selecciona el motivo</option>
                                    @foreach($motives as $motive)
                                        <option value="{{ $motive->id }}" {{ old('motive_id') == $motive->id ? 'selected' : '' }}>
                                            [{{ strtoupper($motive->type) }}] - {{ $motive->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('motive_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Persona --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1">Persona que Entrega</label>
                                <input type="text" name="delivered_by" value="{{ old('delivered_by') }}" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
                                @error('delivered_by') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Auditoría --}}
                            <div class="md:col-span-2 flex items-start mt-3">
                                <input id="is_audit" name="is_audit" type="checkbox" value="1"
                                    class="h-5 w-5 text-red-600 rounded bg-gray-100 dark:bg-gray-800 border-gray-400"
                                    {{ old('is_audit') ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <label class="font-semibold">¿Es de Auditoría (Urgente)?</label>
                                    <p class="text-sm text-red-500">Marcar si la prenda requiere atención inmediata</p>
                                </div>
                            </div>

                        </div>

                        {{-- BOTONES --}}
                        <div class="flex justify-end mt-8 gap-4 border-t dark:border-gray-700 pt-4">
                            <a href="{{ route('garments.index') }}"
                               class="px-5 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Cancelar
                            </a>

                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                                Registrar ENTRADA
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
