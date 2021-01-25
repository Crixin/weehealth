<div class="col-md-6">
    <div class="card card-outline-info">
        <div class="card-header">
            <h4 class="m-b-0 text-white">Este documento está em revisão -  Previsão Próxima revisão: <b>{{-- {{ \Carbon\Carbon::createFromFormat('Y-m-d', $validadeDoc)->format('d/m/Y') }} --}}</b>  </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="card-text">Você pode cancelar a revisão à qualquer momento clicando no botão ao lado.</p>
                </div>
                <div class="col-md-12 m-t-20">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirm-cancel-review-modal">
                        Launch demo modal
                      </button>
                    {{-- <button type="button" class="btn btn-block btn-danger" data-toggle="modal" data-target="#confirm-cancel-review-modal" >Cancelar Revisão</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-cancel-review-modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
