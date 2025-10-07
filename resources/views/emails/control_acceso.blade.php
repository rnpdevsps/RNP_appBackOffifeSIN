<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #1e5b93; color: #fff; }
        h2 { color: #1e5b93; }
    </style>
</head>
<body>
    <h2>Notificación de Control de Acceso</h2>
    <p>Se ha registrado una nueva excepción de huella con los siguientes detalles:</p>

    <table>
        <tr><th>Nombre</th><td>{{ $nombre }}</td></tr>
        <tr><th>No. Documento</th><td>{{ $nodoc }}</td></tr>
        <tr><th>Tipo de Documento</th><td>{{ $tipodoc }}</td></tr>
        <tr><th>Institución</th><td>{{ $institucion }}</td></tr>
        <tr><th>Área</th><td>{{ $area }}</td></tr>
        <tr><th>Nivel</th><td>{{ $nivel }}</td></tr>
        <tr><th>Reunión</th><td>{{ $reunion }}</td></tr>
        <tr><th>Solicitante</th><td>{{ $solicitante }}</td></tr>
        <tr><th>Fecha</th><td>{{ $fecha }}</td></tr>
        <tr><th>Hora</th><td>{{ $Hora }}</td></tr>
    </table>

    <br>
    <p>Saludos cordiales,</p>
    <p>Departamento de Informática</p>
</body>
</html>
