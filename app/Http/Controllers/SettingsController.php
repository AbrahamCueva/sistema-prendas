<?php

namespace App\Http\Controllers;

use App\Traits\HasSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use HasSettings;

    /**
     * Muestra el formulario de edición de la configuración.
     */
    public function edit()
    {
        // Obtener todos los valores actuales (usando fallbacks en caso de que no existan aún)
        $settings = [
            'site_name' => self::getSetting('site_name', 'Sistema de Prendas'),
            'favicon_path' => self::getSetting('favicon_path', null),
            'seo_title' => self::getSetting('seo_title', 'Configuración de SEO'),
            'seo_description' => self::getSetting('seo_description', 'Sistema de gestión de prendas'),
            'seo_keywords' => self::getSetting('seo_keywords', 'lotes, prendas, costura'),
        ];

        return view('settings.edit', compact('settings'));
    }

    /**
     * Procesa la actualización de la configuración y la subida del favicon.
     */
    public function update(Request $request)
    {
        $request->validate([
            // General
            'site_name' => ['required', 'string', 'max:100'],
            'favicon_file' => ['nullable', 'image', 'mimes:ico,png,jpg,jpeg', 'max:2048'],

            // SEO
            'seo_title' => ['required', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
        ]);

        // 1. Gestión de la Subida del Favicon
        if ($request->hasFile('favicon_file')) {
            // Obtener el valor actual para eliminarlo si existe
            $currentFaviconPath = self::getSetting('favicon_path');

            // Si hay un favicon antiguo y es un archivo, intentar eliminarlo
            if ($currentFaviconPath && file_exists(public_path($currentFaviconPath))) {
                unlink(public_path($currentFaviconPath));
            }

            // Guardar el nuevo archivo directamente en la carpeta public/
            $file = $request->file('favicon_file');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path(), $filename);

            // Guardar la ruta relativa del nuevo favicon en la BD
            self::setSetting('favicon_path', $filename);
        }

        // 2. Actualizar la Configuración General y SEO (usando el Trait)
        self::setSetting('site_name', $request->site_name);
        self::setSetting('seo_title', $request->seo_title);
        self::setSetting('seo_description', $request->seo_description);
        self::setSetting('seo_keywords', $request->seo_keywords);

        return redirect()->route('settings.edit')
            ->with('success', 'Configuración del Sitio actualizada correctamente.');
    }
}
