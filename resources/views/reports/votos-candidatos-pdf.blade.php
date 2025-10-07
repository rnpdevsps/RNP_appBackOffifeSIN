<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Votos por Candidato</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .card { border: 1px solid #ccc; margin-bottom: 20px; padding: 10px; }
        .card-header { background: #565656; color: white; padding: 5px 10px; font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        .table-header { background: #e0e0e0; }
        tfoot td { font-weight: bold; background-color: #e0e0e0; }
    </style>
</head>
<body>

    <hr>

    @foreach ($candidatosConVotos as $candidato)
        <div class="card">
            <div class="card-header">
                {{ $candidato['nombre'] }} — DNI: {{ $candidato['dni'] }}
            </div>
            <table>
                <thead class="table-header">
                    <tr>
                        <th>Cargo</th>
                        <th>Cantidad de Votos</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp

                    @forelse ($candidato['votos'] as $puesto => $cantidad)
                        @php $total += $cantidad; @endphp

                        <tr>
                            <td>{{ $puesto }}</td>
                            <td>{{ $cantidad }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No recibió votos</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <td><strong>Total de Votos:</strong></td>
                        <td><strong>{{ $total }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach

</body>
</html>
