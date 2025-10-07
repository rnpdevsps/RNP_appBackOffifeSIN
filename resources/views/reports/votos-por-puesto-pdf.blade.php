<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Ganadores por Puesto</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .card { border: 1px solid #ccc; margin-bottom: 20px; padding: 10px; }
        .card-header { background: #565656; color: white; padding: 5px 10px; font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        .table-header { background: #f0f0f0; }
    </style>
</head>
<body>
    <hr>

<div class="card">
    <div class="card-header">
        Total de votos por cargo
    </div>
    <table>
        <thead class="table-header">
            <tr>
                <th>Cargo</th>
                <th>Nombre del Candidato</th>
                <th>DNI</th>
                <th>Total de Votos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resumen as $r)
                <tr>
                    <td>{{ $r['puesto'] }}</td>
                    <td>{{ $r['nombre'] }}</td>
                    <td>{{ $r['dni'] }}</td>
                    <td>{{ $r['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
