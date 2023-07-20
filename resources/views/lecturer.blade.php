@extends('layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Dashboard</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="box">

        <div class="box-body">
          <div id="panel-1" class="panel" data-panel-close="" data-panel-locked="" data-panel-refresh="" data-panel-reset="" data-panel-custombutton="" data-panel-color="">
            {{-- <div class="panel-hdr bg-info-700 bg-success-gradient"></div> --}}
            <ul class="nav nav-tabs nav-bordered mb-3">
              <li class="nav-item">
                <a class="nav-link active" id="v-pills-courselist-tab" controller="" table-data="table_courselist" data-bs-toggle="tab" data-toggle="pill" href="#v-pills-courselist" role="tab" aria-controls="v-pills-courselist" aria-selected="true">
                  <div class=" d-md-block">
                    <i class="fa fa-list"></i>
                    <span class="hidden-sm-down ml-1">Courses</span>
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
                  <label class="form-label">Session</label>
                  <select id="session" class="form-select">
                    <option selected value="" disabled>-</option>
                    @foreach ($sessions as $ses)
                    <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                    @endforeach
                  </select>
                  <label id="coursestatus_error" class="text-danger small error-field"></label>
                </div>
              </div>
          
              <!--<div class="col-md-5">
                  <a href="" class="waves-effect waves-light btn btn-primary mb-5 pull-right"><i class="fa fa-plus"></i> Create</a>
              </div>-->
            </div>
          </div>
        </div>
      </div>
      <div id="courselist">
        <div class="row">
          @foreach ($data as $key)
          <div class="col-md-4">
            <div class="card" style="padding:0 !important; height:32em">
              <div class="row g-0 fx-element-overlay g-0 align-items-center">
                <div class="col-md-12">
                  <div class="fx-card-item">
                    <div class="fx-card-avatar fx-overlay-1" style="cursor:pointer">
                      <img src="" style="height:auto !important; max-height:250px !important" onerror="this.onerror=null;this.src='{{ asset('assets/images/uniti.jpg') }}';" 
                      class="bber-0 bbsr-0" alt="...">
                      <div class="fx-overlay ">
                        <ul class="fx-info">
                          <li>
                            <a href="/lecturer/{{ $key->id }}?session={{ $key->SessionID }}" class="btn btn-primary-outline mr-1" data-toggle="tooltip" data-placement="auto" ><i class="fa fa-paper-plane"></i> View</a>
                          </li> 
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <span class="badge badge-warning pull-right">ACTIVE</span>
                  <div class="card-body">
                    <div class="p-2">
                      <div class="row">
                          <div class="col-md-12">
                            <h5 class="card-title fw-600">{{ ucwords($key->course_name) }}</h5>
                            <p class="card-text text-gray-600 pt-5">
                              <strong>Code</strong> {{ ucwords($key->course_code) }}
                            </p>
                            <!--<p class="card-text text-gray-600">
                              <strong>Faculty</strong> {{ ucwords($key->course_code) }}
                            </p>-->
                            <p class="card-text text-gray-600">
                              <strong>Session</strong> {{ ucwords($key->SessionName) }}
                            </p>
                          </div>
                        </div>
                    </div>
                  </div> <!-- end card-body-->
                </div> <!-- end col -->
              </div> <!-- end row-->
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script type="text/javascript">
  var search_input = "";
  var selected_session = "";
 
  
    $(document).on('keyup', '#search-txt', async function(e){
      search_input = $(e.target).val();
  
      await getCourseList(search_input);
    })
  
    $(document).on('change', '#session', async function(e){
      selected_session = $(e.target).val();
  
      await getCourseList(search_input,selected_session)
    })
  
    function getCourseList(search,session)
    {
      return $.ajax({
              headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
              url      : "{{ url('lecturer/course/filter') }}",
              method   : 'POST',
              data 	 : {search: search,session: session},
              error:function(err){
                  alert("Error");
                  console.log(err);
              },
              success  : function(data){
                  //$('#lecturer-selection-div').removeAttr('hidden');
                  $('#courselist').html(data);
                  //$('#lecturer').selectpicker('refresh');
  
              }
          });
    }
  
  </script>

@endsection

