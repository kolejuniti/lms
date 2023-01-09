@extends('../layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Lecturer Report</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Admin</li>
              <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lecturer's Course Content</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body d-flex">
                  <div class="box-body col-4" style="border: 1px solid blue;margin-right: 20px;">
                    <table class="table" id="myTable">
                      <tbody class="table-body">
                          @foreach ($faculty as $key => $fcl)
                          <tr class="cell-1" data-toggle="collapse" data-target="#demo-{{ $fcl->id }}">
                              <td>
                                  <i class="ti-book" style="margin-right: 5px;"></i> 
                                  {{ $fcl->facultyname }}
                              </td>
                          </tr>
                          @foreach ($lecturer[$key] as $key2 => $lct)
                          <tr id="demo-{{ $fcl->id }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-{{ $lct->ic }}">
                              <td>
                                  <div style="margin-left: 20px;">
                                      <i class="ti-user" style="margin-right: 5px;"></i>
                                      {{ $lct->name }}
                                      @if($lct->lastLogin != null)
                                      <a class="btn btn-success pull-right btn-sm pr-2" onclick="getUser('{{ $lct->ic }}')" data-toggle="modal" data-target="#userLog">Last Logged : {{ $lct->lastLogin }}</a>
                                      @else
                                      <a class="btn btn-danger pull-right btn-sm pr-2" data-toggle="modal" data-target="#userLog">Not Logged</a>
                                      @endif
                                  </div>
                              </td>
                          </tr>
                          @foreach ($course[$key][$key2] as $key3 => $crs)
                          <tr id="demo-{{ $lct->ic }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-{{ $crs->id }}" onclick="tryerr0('{{ $crs->id }}','{{ $lct->ic }}','{{ $crs->SessionID }}')">
                              <td>
                                  <div style="margin-left: 40px;">
                                      <i class="ti-folder" style="margin-right: 5px;"></i>
                                      {{ $crs->course_name }} ({{ $crs->course_code }} / {{ $crs->SessionName }})
                                  </div>
                              </td>
                          </tr>
                          @endforeach
                          @endforeach
                          @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="box-body col-md-7 mt-2">
                    <div id="showMaterial">

                    </div>
                  </div>
              </div>
            </div>
            <!-- /.card -->
            <div id="uploadModal" class="modal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                  <!-- modal content-->
                  <div class="modal-content" id="getModal">
                      <form action="#" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <div class="">
                                <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                          <div class="row col-md-12">
                            <div>
                              <div class="form-group">
                                <label>From (Start)</label>
                                <input type="date" name="from" id="from" class="form-control">
                              </div>
                            </div>
                            <div>
                              <div class="form-group">
                                <label>To (End)</label>
                                <input type="date" name="to" id="to" class="form-control">
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="form-group mt-3">
                                        <label class="form-label">Asessment List</label>
                                        <table id="claim_list" class="table table-striped projects display dataTable">
                                        </table>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                      </form>
                  </div>
              </div>
            </div>

            <div id="userLog" class="modal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                  <!-- modal content-->
                  <div class="modal-content" id="getModal">
                      <form action="#" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <div class="">
                                <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                          <div class="row col-md-12">
                            <div>
                              <div class="form-group">
                                <label>From (Start)</label>
                                <input type="date" name="from_log" id="from_log" class="form-control">
                              </div>
                            </div>
                            <div>
                              <div class="form-group">
                                <label>To (End)</label>
                                <input type="date" name="to_log" id="to_log" class="form-control">
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="form-group mt-3">
                                        <label class="form-label">User Log</label>
                                        <table id="log_list" class="table table-striped projects display dataTable">
                                        </table>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
$(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );

function tryerr0(id,ic,ses)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('admin/report/lecturer/getFolder') }}",
        method   : 'POST',
        data 	 : {id: id,ic: ic,ses: ses},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

  
function tryerr(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('admin/report/lecturer/getSubfolder') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

  
  //alert(id);
}

function tryerr2(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('admin/report/lecturer/getSubfolder/getSubfolder2') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function tryerr3(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('admin/report/lecturer/getSubfolder/getSubfolder2/getMaterial') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });
}

$(document).on('change', '#from', async function(e){
    selected_from = $(e.target).val();

    await getAssessment(selected_from,selected_to);
});

$(document).on('change', '#to', async function(e){
    selected_to = $(e.target).val();

    await getAssessment(selected_from,selected_to);
});

function getAssessment(from,to)
{

  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('admin/report/lecturer/getAssessment') }}",
        method   : 'POST',
        data 	 : {from: from,to: to},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            //$('#chapter').removeAttr('hidden');
                $('#claim_list').html(data);
                //$('#chapter').selectpicker('refresh');
        }
    });

}

$(document).on('change', '#from_log', async function(e){
    selected_from = $(e.target).val();

    await getUserLog(selected_from,selected_to);
});

$(document).on('change', '#to_log', async function(e){
    selected_to = $(e.target).val();

    await getUserLog(selected_from,selected_to);
});

function getUser(ic)
{

  user = ic;

}

function getUserLog(from,to)
{

  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('admin/report/lecturer/getUserLog') }}",
        method   : 'POST',
        data 	 : {from: from,to: to,user: user},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            //$('#chapter').removeAttr('hidden');
                $('#log_list').html(data);
                //$('#chapter').selectpicker('refresh');
        }
    });

}

</script>
@endsection
