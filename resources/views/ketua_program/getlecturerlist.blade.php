@foreach($lecturer as $lect)

<div class="box mb-15 pull-up">
<div class="box-body">
    <div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <div class="me-15 bg-warning w-150 l-h-55 rounded text-center">
        <img src="{{ asset('assets/images/card/1.jpg') }}" style="height:auto !important; max-height:250px !important" onerror="this.onerror=null;this.src='{{ asset('assets/images/1.jpg') }}';" 
            class="bber-0 bbsr-0" alt="...">
        {{-- <span class="fs-24">{{ substr($row->coursename, 0, 1) }}</span> --}}
        </div>
        <div class="d-flex flex-column">
            <a  class="text-dark mb-1 fs-16">{{ $lect->name }}</a>
            <span class="text-fade">{{ $lect->ic }}</span>
            <div class="mt-2">
            </div>
        </div>
    </div>
    
    
    <div class="d-flex align-items-center">
        <div class="dropdown ">
            <button class="btn btn-outline btn-sm btn-dark border-0" type="button" data-bs-toggle="dropdown" aria-expanded="true">
            <i class="glyphicon glyphicon-option-vertical"></i></button>
            <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 41px);">
                <a href="/KP/lecturer/report/{{ $lect->id }}" class="dropdown-item" data-placement="auto" title="View Report"><i class="fa fa-edit"></i>View Report</a>
                <!--<a href="javascript:void(0);" onclick="deleteCourse({{ $lect->id }});" class="dropdown-item" data-toggle="tooltip" data-placement="auto" title="Delete Record"><i class="fa fa-trash"></i>Delete</a>
            <div class="dropdown-divider"></div></div>-->
            </div>
        </div>
    </div>
    </div>
</div>
</div>
@endforeach