<x-app-layout>
    <x-slot name="title">
        {{ __('Registro de Entrada de Lote de Prendas') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registro de Entrada de Lote de Prendas') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-200">

                    {{-- CAMBIO CLAVE: Se añade enctype para la subida de archivos --}}
                    <form method="POST" action="{{ route('garments.store') }}" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-xl font-semibold mb-6 text-indigo-600 dark:text-indigo-400 border-b dark:border-gray-700 pb-2">
                            Datos del Lote de Prendas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            {{-- ... (PV y Talla, sin cambios) ... --}}

                            {{-- PV --}}
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold mb-1">PV (Código)</label>
                                <input id="pv" type="text" name="pv" value="{{ old('pv', $randomPV) }}" required maxlength="5"
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('pv') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Talla --}}
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold mb-1">Talla</label>
                                <input id="size" type="text" name="size" value="{{ old('size') }}" required
                                    placeholder="Ej: S, M, L, 32"
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('size') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Cantidad (Cambio de 'quantity' a 'quantity_in') --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1">Cantidad de Prendas (Lote Completo)</label>
                                <input id="quantity_in" type="number" name="quantity_in" value="{{ old('quantity_in', 1) }}" required min="1"
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('quantity_in') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- ... (El resto del formulario, sin cambios) ... --}}

                            {{-- Color --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1">Color de la Prenda</label>
                                <input id="color" type="text" name="color" value="{{ old('color') }}" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('color') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Cliente --}}
                            <div class="md:col-span-2">
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
                            <div class="md:col-span-2">
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

                            {{-- FOTO DEL DEFECTO --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">
                                    Foto del Defecto (Opcional)
                                </label>
                                <input id="defect_photo" type="file" name="defect_photo" accept="image/*"
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 p-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sube una imagen (JPG, PNG) para documentar el problema.</p>
                                @error('defect_photo') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Persona --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1">Persona que Entrega el Lote</label>
                                <input type="text" name="delivered_by" value="{{ old('delivered_by') }}" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600">
                                @error('delivered_by') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Auditoría/Prioridad --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-1">Nivel de Auditoría/Urgencia</label>
                                <select name="audit_level" required
                                    class="w-full rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="normal" {{ old('audit_level') == 'normal' ? 'selected' : '' }}>Normal (Atención Estándar)</option>
                                    <option value="urgente" {{ old('audit_level') == 'urgente' ? 'selected' : '' }}>Urgente (Requiere Auditoría Rápida)</option>
                                </select>
                                @error('audit_level') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
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
                                Registrar ENTRADA de Lote
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
