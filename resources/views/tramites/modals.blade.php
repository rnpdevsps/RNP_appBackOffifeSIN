<!-- Add delete modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Eliminar Compareciente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Esta seguro que desea eliminar compareciente?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
      </div>
    </div>
  </div>
</div>


<!-- Add delete modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Compareciente Existente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="duplicateModalLabel">Compareciente Existente</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Payment modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pagar Tramite</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          @include('tramites.payment')
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Pagar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Compareciente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addForm">
          <div class="mb-3">
          
            
            <label for="dniApi" class="col-form-label">Identidad</label>
            <input type="text" autocomplete="off" class="form-control" maxlength="15"  id="dniApi" name="dniApi" required placeholder="0501-0000-00000">
            <p id="errorDNI" style="display: none;"><strong style="color: #d70b0b;">{{__('DNISinPermiso')}}</strong></p>
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" readonly autocomplete="off" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <input type="hidden" autocomplete="off" class="form-control" id="actionsInput" name="actions" value='<a href="#" class="btn btn-primary btn-sm" ><i class="ti ti-edit"></i> Validar</a>'>
          </div>
          <div class="mb-3">
            <input type="hidden"  autocomplete="off" class="form-control" id="authInput" name="auth" value='<span class="p-2 px-3 badge rounded-pill bg-warning">Pendiente</span>'>
          </div>
        </form>
      </div>
      <div class="modal-footer" id="btnDNI" style="display: none;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="addRowFromModal()">Agregar</button>
      </div>
    </div>
  </div>
</div>