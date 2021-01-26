<div class="row">
    <div class="col col-centered">
        <div class="collapse multi-collapse" id="multiCollapseExample2">
            <div class="card card-body text-center">
                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <div class="row" style="font-size:14px">
                            <div class="form-group col-md-12">
                                <?php \Carbon\Carbon::setLocale('pt_BR') ?>
                                <ul class="timeline text-center">
                                    @foreach( $historico as $key => $hist )
                                        <li class=" {{ $key%2 == 0 ? 'timeline-inverted' : '' }}">
                                            <div class="timeline-badge success"  style="display: flex;justify-content: center">
                                                <i class="mdi mdi-file-document" style="display: flex;align-items: center"></i>
                                            </div>
                                            <div class="timeline-panel">
                                                <div class="timeline-heading" >
                                                    <h4 class="timeline-title"><b>{{ ($hist->coreUsers->name != null) ? $hist->coreUsers->name : 'Usuário Inválido' }}</b></h4>
                                                    <h7 class="timeline-title"><b>{{ __('page_titles.docs.documento.stage_create')}}</b>{{' ' . date('d/m/Y h:i:s', strToTime($hist->created_at))}}</h7>
                                                    <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ $hist->created_at->diffForHumans() }}</small> </p>
                                                    <div class="timeline-body">
                                                        <b>{{ $hist->descricao }}</b>
                                                    </div>
                                                    <p>
                                                        <small class="text-muted">
                                                            <b>{{ __('page_titles.docs.documento.stage_time')}}</b>
                                                            {{$hist->tempo_duracao_etapa}}
                                                        </small>
                                                    </p>
                                                </div>
                                                
                                            </div>
                                        </li>
                                    @endforeach     
                                </ul>
                            </div>
                        </div>
                    </div>    
                </div>
                
            </div>
        </div>
    </div>
</div>