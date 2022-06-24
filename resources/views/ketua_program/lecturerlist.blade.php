
@extends('../layouts.ketua_program')

@section('main')


<style>
	.table.dataTable  {
		font-size: 1em;
	}
	.loadingoverlay{
		padding-top:15em;
		justify-content: start !important;
	}

	ol li{
		list-style-type: none;
	}
	input[type="checkbox"].filled-in:checked + label:after{

	}

	
</style> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Lecturer List</h4>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Dashboard</li>
								<li class="breadcrumb-item active" aria-current="page">Lecturer List</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>

		<!-- Main content -->
		<section class="content">
			
			<div class="row">
				<div class="col-xl-12">
					<div class="box">

						<div class="box-body">
							<div id="panel-1" class="panel" data-panel-close="" data-panel-locked="" data-panel-refresh="" data-panel-reset="" data-panel-custombutton="" data-panel-color="">
								{{-- <div class="panel-hdr bg-info-700 bg-success-gradient"></div> --}}
								<ul class="nav nav-tabs nav-bordered mb-3">
									<li class="nav-item">
										<a class="nav-link active" id="v-pills-courselist-tab" controller="" table-data="table_courselist" data-bs-toggle="tab" data-toggle="pill" href="#v-pills-courselist" role="tab" aria-controls="v-pills-courselist" aria-selected="true">
											<div class=" d-md-block">
												<i class="fa fa-list"></i>
												<span class="hidden-sm-down ml-1">Lecturers</span>
											</div>
										</a>
									</li>
								</ul>
								<div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Keyword</label>
                                            <div class="input-group"> 
                                                <input id="search-txt" placeholder="search keywords..." type="text" class="form-control" autocomplete="off" aria-invalid="false" value=""> <span class="input-group-addon text-muted "><i class="fa fa-search p-2"></i></span> 
                                            </div>
                                        </div>
                                    </div>
                                        
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <select id="coursestatusselect" class="form-select">
                                                <option value=1>active</option>
                                                <option value=2>not active</option>
                                            </select>
                                            <label id="coursestatus_error" class="text-danger small error-field"></label>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Sort By</label>
                                            <div class="controls">
                                                <select class="form-select" name="sortby-select" id="sortby-select" required="" class="" aria-invalid="false">
                                                    <option value="0">Name Ascending</option>
                                                    <option value="1">Name Descending</option>
                                                    <option value="2">Created Date Ascending</option>
                                                    <option value="3">Created Date Descending</option>
                                                    <option value="4">Updated Date Ascending</option>
                                                    <option value="5">Updated Date Descending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-5">
                                        <a href="" class="waves-effect waves-light btn btn-primary mb-5 pull-right"><i class="fa fa-plus"></i> Create</a>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				
					<div class="row">
						<div class="col-md-12">
							<div class="">
								<div class="box-body p-0">
									<div class="tab-content" id="v-pills-tabContent">
									
                                    @if(count($lecturer) < 1)
                                    <div class=" d-flex justify-content-center align-items-center box-header bg-secondary-light" style="height:20em">
                                        <h1 class="text-muted ">-- Lecturer Not Found --</h1>
                                    </div>
                                    @else
                                        {{ $lecturer->links('vendor.pagination.bootstrap-5') }}
                                    @endif

                                    @php $i = 1; @endphp

                                    <div id="lecturer-list">
                                    
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
                                    </div>
                                    <br>
                                    @if(!empty($lecturer))
                                    {{ $lecturer->links('vendor.pagination.bootstrap-5') }}
                                    @endif
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
var input_search = "";

$(document).on('keyup', '#search-txt', async function(e)
{
    input_search = $(e.target).val();

    await getLecturer(input_search);
})

function getLecturer(search)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/lecturer/filter') }}",
            method   : 'POST',
            data 	 : {search: search},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                //$('#lecturer-selection-div').removeAttr('hidden');
                $('#lecturer-list').html(data);
                //$('#lecturer').selectpicker('refresh');

            }
        });
  }



</script>
	
@stop