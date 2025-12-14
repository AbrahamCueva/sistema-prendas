<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Auditoría #{{ $activity->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #333; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 20px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        .data-table th { background-color: #f2f2f2; }
        .red { color: #c0392b; text-decoration: line-through; }
        .green { color: #27ae60; }
        .details { margin-bottom: 20px; }
        .details p { margin: 5px 0; }
    </style>
</head>
<body>

    <h1>Reporte de Auditoría: Evento #{{ $activity->id }}</h1>

    <div class="details">
        <h2>Información General</h2>
        <p><strong>Fecha y Hora:</strong> {{ $activity->created_at->format('Y-m-d H:i:s') }}</p>
        <p><strong>Usuario:</strong> {{ $activity->causer ? $activity->causer->name : 'Sistema/Desconocido' }}</p>
        <p><strong>Evento:</strong> {{ ucfirst($activity->description) }}</p>
        <p><strong>Modelo Afectado:</strong> {{ Str::afterLast($activity->subject_type, '\\') }} (ID: {{ $activity->subject_id }})</p>
    </div>

    @if ($activity->changes && !empty($activity->changes['attributes']))
        <h2>Propiedades Modificadas</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor Anterior</th>
                    <th>Nuevo Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activity->changes['attributes'] as $key => $newValue)
                    @php
                        $oldValue = $activity->changes['old'][$key] ?? '— NUEVO REGISTRO —';

                        $isDate = in_array($key, ['created_at', 'updated_at', 'delivery_out_date', 'delivery_in_date']) || is_numeric(strtotime($newValue));
                        $displayNewValue = $isDate ? (is_numeric(strtotime($newValue)) ? \Carbon\Carbon::parse($newValue)->format('Y-m-d H:i:s') : $newValue) : $newValue;
                        $displayOldValue = $isDate && $oldValue != '— NUEVO REGISTRO —' ? (\Carbon\Carbon::parse($oldValue)->format('Y-m-d H:i:s') ?? $oldValue) : $oldValue;
                        if ($oldValue === '— NUEVO REGISTRO —') {
                            $displayOldValue = '---';
                        }
                    @endphp
                    <tr>
                        <td>{{ $key }}</td>
                        <td class="red">{{ $displayOldValue }}</td>
                        <td class="green">{{ $displayNewValue }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se registraron cambios específicos de atributos para este evento (Creación o evento simple).</p>
    @endif

</body>
</html>
