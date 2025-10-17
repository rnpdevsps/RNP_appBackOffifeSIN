@extends('layouts.main')
@section('title', __('ApiLogs'))
@section('breadcrumb')
    <div class="col-md-12">
        <div class="page-header-title">
            <h4 class="m-b-10">{{ __('ApiLogs') }}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">{!! html()->a(route('home'), __('Dashboard')) !!}</li>
            <li class="breadcrumb-item active">{{ __('ApiLogs') }}</li>
        </ul>
        
    </div>
@endsection


@section('content') 

<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = new bootstrap.Modal(document.getElementById('responseModal'));
    const responseContent = document.getElementById('responseContent');

    document.querySelectorAll('.view-request').forEach(btn => {
        btn.addEventListener('click', function() {
            const uuid = this.dataset.uuid;

            fetch(`{{ url('logs') }}/${uuid}/request`)
                .then(res => res.json())
                .then(data => {
                    try {
                        const json = JSON.parse(data.request_body);
                        responseContent.textContent = JSON.stringify(json, null, 4);
                    } catch(e) {
                        responseContent.textContent = data.request_body;
                    }
                    modal.show();
                })
                .catch(err => {
                    console.error(err);
                    responseContent.textContent = "Error al cargar el request";
                    modal.show();
                });
        });
    });
    
    document.querySelectorAll('.view-response').forEach(btn => {
        btn.addEventListener('click', function() {
            const uuid = this.dataset.uuid;

            fetch(`{{ url('logs') }}/${uuid}/response`)
                .then(res => res.json())
                .then(data => {
                    try {
                        const json = JSON.parse(data.response_body);
                        responseContent.textContent = JSON.stringify(json, null, 4);
                    } catch(e) {
                        responseContent.textContent = data.response_body;
                    }
                    modal.show();
                })
                .catch(err => {
                    console.error(err);
                    responseContent.textContent = "Error al cargar la respuesta";
                    modal.show();
                });
        });
    });
});
</script>



<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">📜 Bitácora de Logs de API</h3>

    <!-- 🔍 Filtros -->
    <form method="GET" action="{{ route('logs.index') }}" class="row g-2 mb-4">
        <div class="col-md-2">
            <label class="form-label">Fecha desde</label>
            <input type="date" name="from" 
                   value="{{ request('from', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                   class="form-control">
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Fecha hasta</label>
            <input type="date" name="to" 
                   value="{{ request('to', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                   class="form-control">
        </div>

        <div class="col-md-2">
            <label class="form-label">Servicio</label>
            <select name="service_name" class="form-select">
                <option value="">Todos</option>
                @foreach($permisosDisponibles as $service)
                    <option value="{{ $service }}" {{ request('service_name') == $service ? 'selected' : '' }}>
                        {{ $service }}
                    </option>
                @endforeach
            </select>
        </div>

        
        <div class="col-md-2">
            <label class="form-label">Método</label>
            <select name="method" class="form-select">
                <option value="">Todos</option>
                @foreach(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'] as $m)
                    <option value="{{ $m }}" {{ request('method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado HTTP</label>
            <select name="http_status" class="form-select">
                <option value="">Todos</option>
                <option value="2" {{ request('http_status') == '2' ? 'selected' : '' }}>2xx</option>
                <option value="4" {{ request('http_status') == '4' ? 'selected' : '' }}>4xx</option>
                <option value="5" {{ request('http_status') == '5' ? 'selected' : '' }}>5xx</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Resultado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Éxito</option>
                <option value="fail" {{ request('status') == 'fail' ? 'selected' : '' }}>Fallido</option>
                <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>Error</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por DNI / No. Secuencia">
        </div>
        <div class="col-md-6 text-end mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filtrar
            </button>
            <a href="{{ route('logs.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i> Limpiar
            </a>
        </div>
    </form>

    <!-- 🧾 Tabla de Logs -->
    <div class="table-responsive shadow rounded">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Servicio</th>
                    <th>Método</th>
                    <th>Endpoint</th>
                    <th>HTTP</th>
                    <th>Resultado</th>
                    <th>Tiempo (ms)</th>
                    <th>Request</th>
                    <th>Response</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->service_name }}</td>
                        <td><span class="badge bg-secondary">{{ $log->method }}</span></td>
                        <td>{{ $log->endpoint }}</td>
                        <td>
                            <span class="badge 
                                @if($log->http_status >= 500) bg-danger
                                @elseif($log->http_status >= 400) bg-warning
                                @elseif($log->http_status >= 200) bg-success
                                @else bg-secondary @endif">
                                {{ $log->http_status }}
                            </span>
                        </td>
                        <td>
                            <span class="badge 
                                @if($log->status == 'success') bg-success 
                                @elseif($log->status == 'fail') bg-danger 
                                @elseif($log->status == 'error') bg-warning 
                                @else bg-secondary @endif">
                                {{ strtoupper($log->status) }}
                            </span>
                        </td>
                        <td>{{ $log->execution_time_ms }}</td>
                        <td>
                            @if($log->request_body)
                                <button class="btn btn-sm btn-outline-warning view-request" 
                                        data-uuid="{{ $log->uuid }}">
                                    Ver Request Body
                                </button>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($log->response_body)
                                <button class="btn btn-sm btn-outline-info view-response" 
                                        data-uuid="{{ $log->uuid }}">
                                    Ver Response Body
                                </button>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-3">No hay registros en la bitácora</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 🔄 Paginación -->
    <div class="d-flex justify-content-end mt-3">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>

<!-- 📋 Modal para mostrar response_body -->
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark">
            <h5 class="modal-title" id="responseModalLabel" style="color: #ffffff !important;">Response Body</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

      <div class="modal-body bg-light">
        <pre id="responseContent" class="p-3 bg-white rounded border text-dark" style="max-height: 500px; overflow-y: auto;"></pre>
      </div>
    </div>
  </div>
</div>



@endsection



