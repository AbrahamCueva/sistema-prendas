<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información de Perfil Profesional') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Actualice el nombre, foto, correo electrónico, puesto y línea de trabajo de su cuenta.') }}
        </p>
    </header>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" x-data="{ photoName: null, photoPreview: null }">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="photo" :value="__('Foto de Perfil')" />
            <input type="file"
                   id="photo"
                   name="photo"
                   class="hidden"
                   x-ref="photo"
                   x-on:change="
                       photoName = $refs.photo.files[0].name;
                       const reader = new FileReader();
                       reader.onload = (e) => {
                           photoPreview = e.target.result;
                       };
                       reader.readAsDataURL($refs.photo.files[0]);
                   "
            />
            <div class="flex items-center space-x-4 mt-2">
                <div class="shrink-0">
                    @if ($user->profile_photo_path)
                        <img x-show="!photoPreview"
                             src="{{ asset('storage/' . $user->profile_photo_path) }}"
                             class="rounded-full h-20 w-20 object-cover border border-gray-300 dark:border-gray-600"
                             alt="{{ $user->name }}">
                    @else
                        <div x-show="!photoPreview"
                             class="rounded-full h-20 w-20 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-3xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <img x-show="photoPreview"
                         :src="photoPreview"
                         class="rounded-full h-20 w-20 object-cover border border-indigo-500"
                         alt="Nueva Foto">
                </div>

                <div class="space-y-2">
                    <x-secondary-button type="button" x-on:click.prevent="$refs.photo.click()" class="me-2">
                        {{ __('Seleccionar Nueva Foto') }}
                    </x-secondary-button>
                    @if ($user->profile_photo_path)
                        <button type="button" class="text-sm text-red-600 dark:text-red-400 hover:text-red-800"
                                onclick="document.getElementById('delete-photo-form').submit();">
                            {{ __('Eliminar Foto Actual') }}
                        </button>
                    @endif
                    <x-input-error for="photo" class="mt-2" :messages="$errors->get('photo')" />
                </div>
            </div>
        </div>
        <div>
            <x-input-label for="name" :value="__('Nombre Completo')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Su dirección de correo no está verificada.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Haga clic aquí para reenviar el email de verificación.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <div>
            <x-input-label for="job_title" :value="__('Puesto de Trabajo / Rol')" />
            {{-- Usamos $user->job_title para obtener el valor actual del modelo --}}
            <x-text-input id="job_title" name="job_title" type="text" class="mt-1 block w-full" :value="old('job_title', $user->job_title)" autocomplete="organization-title" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ej: Auditor de Calidad, Jefe de Producción.</p>
            <x-input-error class="mt-2" :messages="$errors->get('job_title')" />
        </div>
        <div>
            <x-input-label for="assigned_line" :value="__('Línea/Área Asignada')" />
            <x-text-input id="assigned_line" name="assigned_line" type="text" class="mt-1 block w-full" :value="old('assigned_line', $user->assigned_line)" autocomplete="department" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ej: Línea 5, Taller de Acabados.</p>
            <x-input-error class="mt-2" :messages="$errors->get('assigned_line')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
@if ($user->profile_photo_path)
    <form id="delete-photo-form" method="post" action="{{ route('profile.delete-photo') }}" class="hidden">
        @csrf
        @method('delete')
    </form>
@endif
