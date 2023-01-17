<div id="material-directory">
    <div class="row">
        @foreach ($assign as $ag)
            <div class="col-md-3">
                <a href="#">
                    <div class="box m-1 b-1 border-primary shadow-sm">
                        <div class="box-body h-150">
                            <div class="align-self-center">
                                <h3 class="d-flex justify-content-center">
                                    {{ $ag->title }}
                                </h3>
                                <p class="d-flex justify-content-center">
                                    {{ $ag->statusname }} 
                                </p>

                    
                                <p class="d-flex justify-content-center">
                                    <a href="{{ Storage::disk('linode')->url($ag->content) }}" target="_blank" class="btn btn-primary m-1" data-toggle="tooltip" data-placement="auto" title="Use"><i class="fa fa-download "></i></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            &nbsp&nbsp&nbsp&nbsp
        @endforeach
    </div>
</div>