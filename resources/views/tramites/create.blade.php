@extends('layouts.main')
@section('title', __('Nuevo Tramite'))
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">{{ __('Nuevo Tramite') }}</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">{!! Html::link(route('home'), __('Dashboard'), []) !!}</li>
                        <li class="breadcrumb-item">{!! Html::link(route('tramites.index'), __('Tramites'), []) !!}</li>
                        <li class="breadcrumb-item active">{{ __('Nuevo Tramite') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
@include('layouts.includes.customjs')

    <div class="layout-px-spacing row">
        <div id="basic" class="mx-auto col-lg-12 layout-spacing">
            <div class="statbox card box box-shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="fecha" class="col-form-label">Fecha: {{ $fecha }}</label>
                            </div>
                        </div>
                            
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="notario" class="col-form-label">Notario: {{ $notario }}</label>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="status_tramite" class="col-form-label">Estado del Tramite: {{ $status_tramite }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                {!! Form::open([
                    'route' => ['tramites.store'],
                    'method' => 'POST',
                    'data-validate',
                    'id' => 'form_dataT',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]) !!}

                <div class="card-body">
                                
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row animated slideInLeft">
                                
                                {{ Form::label('maeplantilla_id', __('Tipo de Tramite:'), ['class' => 'col-3 col-form-label']) }}
                                <div class="col-9">
                                {!! Form::select('maeplantilla_id', $plantillas, null, [
                                    'required' => 'required',
                                    'class' => 'form-select',
                                    'id' => 'maeplantilla_id'
                                ]) !!}
                                </div>
                            </div>
                        </div>
                            
                        <div class="col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row animated slideInLeft">
                                
                                {{ Form::label('comment', __('Comentario'), ['class' => 'form-label']) }}
                                {!! Form::textarea('comment', null, [
                                    'placeholder' => 'Escribe un comentario...',
                                    'class' => 'form-control',
                                    'required',
                                    'rows' => 3,
                                ]) !!}
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="row">
                        <div class="col-lg-12">
                        
                        @include('tramites.modals')

                        <!-- Abril Modal para Agregar -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Agregar Compareciente
                        </button>

                          <table id="comparecientesTable" class="table">
                            <thead>
                              <tr>
                                <th>Identidad</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                                <th>Estado Autorización</th>
                                <th>Eliminar</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- body -->
                            </tbody>
                          </table>
                        </div>
                    </div>

                </div>
                
                <div class="card-footer">
                    <div class="float-end">
                        {!! Html::link(route('tramites.index'), __('Cancel'), ['class'=>'btn btn-secondary']) !!}
                        <a href="#" class="btn btn-lg btn-warning" id="submitDataT"  >
                            <span class="btn-inner--icon"><i class="ti ti-send"></i> Guardar Borrador</span>
                        </a>
                        <a href="#" class="btn btn-lg btn-primary rigth" id="submitDataSend"  >
                            <span class="btn-inner--icon"><i class="ti ti-send"></i> Guardar Y Enviar</span>
                        </a>
        
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const firstColumnValues = [];

function addRowFromModal() {
    const table = document.getElementById("comparecientesTable");
  const idInput = document.getElementById("dniApi");
  const idValue = idInput.value;

  if (firstColumnValues.includes(idValue)) {
    document.getElementById("duplicateModalLabel").textContent = `El DNI "${idValue}" ya existe. Ingrese un DNI diferente.`;
    const modal = bootstrap.Modal.getInstance(document.getElementById("exampleModal"));
    modal.hide();
    
    const deleteModal = new bootstrap.Modal(document.getElementById("duplicateModal"));
    deleteModal.show();
    
    return;
  }

  firstColumnValues.push(idValue);

  const row = table.insertRow(-1);

  const idCell = row.insertCell(0);
  idCell.innerHTML = '<input type="text" class="form-control" id="cdni" name="cdni[]" value="'+idValue+' " readonly>';
    
    const nameCell = row.insertCell(1);
    const val = document.getElementById("name").value;
    nameCell.innerHTML = '<input type="text" class="form-control" id="cname" name="cname[]" value="'+val+' " readonly>';
    
    const actionsCell = row.insertCell(2);
    actionsCell.innerHTML = document.getElementById("actionsInput").value;
    
    const authCell = row.insertCell(3);
    authCell.innerHTML = document.getElementById("authInput").value;
    
    const deleteCell = row.insertCell(4);
    const deleteButton = document.createElement("button");
    deleteButton.classList.add("btn", "btn-danger", "btn-sm", "bg-danger");
    deleteButton.textContent = "Eliminar";

    deleteButton.addEventListener("click", () => {
    const firstCellValue = idCell.innerHTML;
    document.getElementById("deleteModalLabel").textContent = `Eliminar Compareciente - ${idValue}`;
    const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    deleteModal.show();
  });
    deleteCell.appendChild(deleteButton);

    const modal = bootstrap.Modal.getInstance(document.getElementById("exampleModal"));
    modal.hide();
    
    document.getElementById("dniApi").value = "";
    document.getElementById("name").value = "";
    document.getElementById("btnDNI").style.display = "none";
    
}

document.getElementById("confirmDeleteButton").addEventListener("click", () => {
  const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
  deleteModal.hide();
  const table = document.getElementById("comparecientesTable");
  table.deleteRow(-1);
});


$('#maeplantilla_id').change(function(){
    var plantillaId = $(this).val();        
    var url = '{{ route("plantilla","") }}';
            
    if (plantillaId > 0) {
        var action = url+"/"+plantillaId;
        var modal = $('#common_modal_xl');
        $.get(action, function(response) {
            modal.find('.modal-title').html('Tramite');
            modal.find('.body').html(response.html);
            modal.modal('show');
        })
    }
    
});


$("#submitDataT").on('click', function(e) {
    let ajaxForm = document.getElementById('form_dataT');
    let fd = new FormData(ajaxForm);
    let url = $("#form_dataT").attr('action');
    let method = $("#form_dataT").attr('method');
    // Agregar un input adicional a FormData
    fd.append('status_tramite', 0);
    var redirect = "{{ route('tramites.index') }}";

    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.error == 1) {
              new swal({
                    text: data.message,
                    icon: 'error',
                });
            } else {
              new swal({
                    text: data.message,
                    icon: "success"
                }).then(() => {
                  window.location.href = redirect;
                })
            }
        },
        error:function(){
          new swal({
                text: "Error.! Datos no registrados.",
                icon: 'error',
            });
        }
    });
});

$("#submitDataSend").on('click', function(e) {
  $("#paymentModal").modal("show");
});


$("#common_modal_xl").on("click", "#submitDataC", function(event) {
    event.preventDefault();

    let ajaxForm = $("#common_modal_xl").find('#form_dataC')[0];
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    let fd = new FormData(ajaxForm);
    let url = $(ajaxForm).attr('action');
    let method = $(ajaxForm).attr('method');
    
    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
            $("#common_modal_xl").modal("hide");
            new swal({
                text: data.success,
                icon: "success"
            }).then(() => {
              $("#divRefreshC").load(location.href + " #divRefreshC", function() {
                });
            });
        },
        error:function(){
          new swal({
                text: "Error.! Intentelo de nuevo.",
                icon: 'error',
            });
        }
    });
});
</script>
@endpush