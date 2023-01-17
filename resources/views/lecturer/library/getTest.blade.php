<div id="material-directory">
    <div class="row">
    @foreach ($test as $ts)
        @if ($ts->date_from != null)
        <div class="col-md-3">
            <a href="#">
                <div class="box m-1 b-1 border-primary shadow-sm">
                    <div class="box-body h-150">
                        <div class="align-self-center">
                            <h3 class="d-flex justify-content-center">
                                {{ $ts->title }} 
                            </h3>
                            <p class="d-flex justify-content-center">
                                {{ sprintf("%0d hour %02d minute",   floor($ts->duration / 60), $ts->duration % 60) }}
                            </p>
                            <p class="d-flex justify-content-center">
                                {{ $ts->statusname }} 
                            </p>
                
                            <p class="d-flex justify-content-center">
                                <a href="/lecturer/test/{{ Session::get('CourseID') }}/create?testid={{ $ts->id }}&REUSE=1" class="btn btn-info m-1" data-toggle="tooltip" data-placement="auto" title="Use"><i class="fa fa-recycle"></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        &nbsp&nbsp&nbsp&nbsp
        @else
        <div class="col-md-3">
            <a href="#">
                <div class="box m-1 b-1 border-primary shadow-sm">
                    <div class="box-body h-150">
                        <div class="align-self-center">
                            <h3 class="d-flex justify-content-center">
                                {{ $ts->title }}
                            </h3>
                            <p class="d-flex justify-content-center">
                                OFFLINE
                            </p>
                            <p class="d-flex justify-content-center">
                                {{ $ts->statusname }} 
                            </p>
                
                            <p class="d-flex justify-content-center">
                                <a href="{{ Storage::disk('linode')->url($ts->content) }}" target="_blank" class="btn btn-primary m-1" data-toggle="tooltip" data-placement="auto" title="Use"><i class="fa fa-download "></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        &nbsp&nbsp&nbsp&nbsp
        @endif
    @endforeach
    </div>
</div>