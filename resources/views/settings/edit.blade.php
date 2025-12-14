<x-app-layout>
    <x-slot name="title">{{ __('Configuración del Sitio') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configuración Avanzada del Sitio') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- Formulario para la subida de archivos (requiere enctype) --}}
            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                    <div class="p-6 sm:px-20 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Identidad y SEO') }}
                        </h3>

                        <div class="grid grid-cols-6 gap-6">

                            {{-- 1. Nombre del Sistema --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="site_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nombre del Sistema') }}</label>
                                <input id="site_name" name="site_name" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    value="{{ old('site_name', $settings['site_name']) }}" required />
                                @error('site_name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            {{-- 2. Favicon --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="favicon_file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Favicon (Ícono del Sitio)') }}</label>
                                @if($settings['favicon_path'])
                                    <p class="text-sm text-gray-500 mb-1">Actual:
                                        <img src="{{ asset($settings['favicon_path']) }}" class="inline w-4 h-4 mr-2" alt="Favicon actual">
                                        {{ $settings['favicon_path'] }}
                                    </p>
                                @endif
                                <input id="favicon_file" name="favicon_file" type="file" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2" />
                                @error('favicon_file') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500">Soporta: .ico, .png, .jpg. Máx 2MB.</p>
                            </div>

                            {{-- 3. Título SEO --}}
                            <div class="col-span-6">
                                <label for="seo_title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Título SEO Global (70 caracteres max)') }}</label>
                                <input id="seo_title" name="seo_title" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    value="{{ old('seo_title', $settings['seo_title']) }}" required />
                                @error('seo_title') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            {{-- 4. Descripción SEO --}}
                            <div class="col-span-6">
                                <label for="seo_description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Descripción SEO (160 caracteres max)') }}</label>
                                <textarea id="seo_description" name="seo_description" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('seo_description', $settings['seo_description']) }}</textarea>
                                @error('seo_description') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            {{-- 5. Palabras Clave SEO --}}
                            <div class="col-span-6">
                                <label for="seo_keywords" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Palabras Clave (Separadas por coma)') }}</label>
                                <input id="seo_keywords" name="seo_keywords" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    value="{{ old('seo_keywords', $settings['seo_keywords']) }}" />
                                @error('seo_keywords') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-end px-6 py-4 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Guardar Configuración') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
