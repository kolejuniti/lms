@extends('layouts.lecturer.lecturer')

@section('main')

<style>
  .auto-height-card {
    min-height: 100px; /* Adjust this value to set a minimum height for the card */
    display: flex;
    flex-direction: column;
  }

  .auto-height-card .card-body {
    flex-grow: 1;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Announcement</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Online Class</li>
                <li class="breadcrumb-item active" aria-current="page">Announcement</li>
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
												<span class="hidden-sm-down ml-1">Online Class</span>
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
                      
                  <!--<div class="col-md-2">
                      <div class="form-group">
                          <label class="form-label">Program</label>
                          <select id="coursestatusselect" class="form-select">
                          </select>
                          <label id="coursestatus_error" class="text-danger small error-field"></label>
                      </div>
                  </div>-->
              
                  <!--<div class="col-md-5">
                      <a href="" class="waves-effect waves-light btn btn-primary mb-5 pull-right"><i class="fa fa-plus"></i> Create</a>
                  </div>-->
              </div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="">
								<div class="box-body p-0">
									<div class="tab-content" id="v-pills-tabContent">
                    @if(count($class) < 1)
                    <div class=" d-flex justify-content-center align-items-center box-header bg-secondary-light" style="height:20em">
                        <h1 class="text-muted ">-- Lecturer Not Found --</h1>
                    </div>
                    @else
                        {{ $class->links('vendor.pagination.bootstrap-5') }}
                    @endif

                    @php $i = 1; @endphp

                    <div id="lecturer-list">
                    
                    @foreach($class as $key => $cls)

                    <div class="box mb-2 mt-1">
                      <div class="media-list media-list-divided media-list-hover">
                        <div class="media align-items-center">
                            <a class="avatar avatar-lg status-success" href="#">
                              <div class="me-15 bg-warning h-50 w-50 l-h-55 rounded text-center">
                                {{-- <img src="{{ asset('storage/'.$row->courseimgsrc) }}"> --}}
                                <span class="fs-24"></span>
                              </div>
                            </a>
                    
                            <div class="media-body" style="margin-top:15px;">
                            <p>
                                <a href="#" href="#"><strong>Group </strong></a>
                                <small class="sidetitle"></small>
                            </p>
                            &nbsp;
                            <p>
                            <strong>Chapters</strong>
                            </p>
                            @foreach ($chapters[$key] as $chp)
                            <p>
                              <small>Chapter {{ $chp->SubChapterNo}} :</small>
                              <small>{{strtoupper($chp->DrName)}}</small>
                            </p>
                            @endforeach

                            
                            <div class="card auto-height-card">
                              <div class="card-body col-md-12">
                                <p>
                                  {!! $cls->classdescription !!}
                                </p>
                              </div>
                            </div>
                          
                            <div class="row" style="margin-top:15px;">
                                <div class="col-sm-8">
                                <p>
                                    <a style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" href="#"><i class="fa fa-link">&nbsp{{ $cls->classlink}}</i></a>
                                </p>
                                </div>
                    
                                <div class="col-sm-4">
                                    <nav class="nav">
                                        <a class="nav-link" href="#"><i class="fa fa-group">&nbsp{{ $totalstd[$key] }}</i></a>
                                        <a class="nav-link" href="#"><i class="fa fa-clock-o"></i> &nbsp{{ $cls->classdate }}</a>
                                        </a>
                                        <a class="nav-link">
                                        </a>
                                    </nav>
                                </div>
                            </div>
                            </div>
                          
                            <div class="media-right gap-items">
                              <div class="dropdown pull-right">
                                <button class="media-action lead align-middle btn btn-outline btn-sm btn-dark border-0" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                  <i class="glyphicon glyphicon-option-vertical"></i></button>
                                  <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 41px);">
                                    <!--<a href="/lecturer/class/onlineclass/list/edit/" class="dropdown-item" data-toggle="tooltip" data-placement="auto" title="Edit Record"><i class="fa fa-edit"></i>Edit</a>-->
                                    <a href="javascript:void(0);" onclick="deleteAnnouncement('{{ $cls->id }}')" class="dropdown-item" data-toggle="tooltip" data-placement="auto" title="Delete Record"><i class="fa fa-trash"></i>Delete</a>
                                  </div>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                    </div>
                    <br>
                    @if(!empty($class))
                    {{ $class->links('vendor.pagination.bootstrap-5') }}
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
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
<script type="text/javascript">

  function deleteAnnouncement(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('lecturer/class/announcement/list/delete') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }

</script>
@endsection
