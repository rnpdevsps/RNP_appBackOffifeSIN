<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>IP Address</th>
            <th>Endpoint</th>
            <th>User Agent</th>
            <th>Status</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->endpoint }}</td>
                <td>{{ $log->user_agent }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $logs->links() }}
