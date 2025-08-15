
@extends('layouts.lecturer.lecturer')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }

        div.dt-buttons {
    float: right;
    margin-left:10px;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="page-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Final</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Final</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="box">
              <div class="card-header mb-4">
                <h3 class="card-title">Final List</h3>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-md-2 mb-4">
                        <div class="form-group">
                          <label class="form-label" for="group">Group</label>
                          <select class="form-select" id="group" name="group" required>
                              <option value="" selected disabled>-</option>
                              @foreach ($group as $grp)
                                <option value="{{ $grp->groupid }}|{{ $grp->groupname }}">Group {{ $grp->groupname }}</option>
                              @endforeach
                          </select>
                          <span class="text-danger">@error('folder')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                      <div id = "status">
                        <div class="col-sm-12">
                          <table id="myTable" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                            <thead>
                              <tr>
                                <th style="width: 1%">
                                  No.
                                </th>
                                <th>
                                  Name
                                </th>
                                <th>
                                  Matric No.
                                </th>
                                <th>
                                  Program
                                </th>
                                <th>
                                  Submission Date
                                </th>
                                <th>
                                  Marks
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($final as $key => $qz)

                              @if ($status[$key]->final_mark != 0)
                                @php
                                  $alert = "badge bg-success";
                                @endphp
                              @else
                                @php
                                  $alert = "badge bg-danger";
                                @endphp
                              @endif
                              
                              <tr>
                                <td style="width: 1%">
                                    {{ $key+1 }}
                                </td>
                                <td>
                                  <span class="{{ $alert }}">{{ $qz->name }}</span>
                                </td>
                                <td>
                                  <span class="">{{ $qz->no_matric }}</span>
                                </td>
                                <td>
                                  <span class="">{{ $qz->progcode }}</span>
                                </td>
                                @if ($status[$key]->final_mark != 0)
                                  
                                  <td>
                                        {{ $status[$key]->submittime}}
                                  </td>                                               
                                @else
                                <td>
                                  -
                                </td>
                                @endif
                                <td>
                                  <div class="form-inline col-md-6 d-flex">
                                      <input  type="number" class="form-control" name="marks[]" max="{{ $qz->total_mark}}" value="{{ $status[$key]->final_mark }}">
                                      <input  type="text" name="ic[]" value="{{ $qz->student_ic }}" hidden>
                                      <span>{{ $status[$key]->final_mark }} / {{ $qz->total_mark}}</span>
                                  </div>
                                </td>
                              </tr> 
                            
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @php
              // Get active assessment period for current user and session
              $currentDate = now()->format('Y-m-d');
              $currentUserIc = auth()->user()->ic;
              $currentSessionId = Session::get('SessionID');
              
              $period = null;
              if ($currentSessionId) {
                  $period = DB::table('tblassessment_period')
                      ->where('Start', '<=', $currentDate)
                      ->where('End', '>=', $currentDate)
                      ->get()
                      ->filter(function ($p) use ($currentUserIc, $currentSessionId) {
                          $userIcs = json_decode($p->user_ic, true) ?: [];
                          $sessions = json_decode($p->session, true) ?: [];
                          
                          return in_array($currentUserIc, $userIcs) && 
                                 in_array($currentSessionId, $sessions);
                      })
                      ->first();
              }
              
              // Determine if box-footer should be visible
              $showFooter = false;
              if (!empty($period)) {
                  if ($period->subject == 'ALL') {
                      $showFooter = true;
                  } else {
                      $courseName = Session::get('CourseIDS')->course_name ?? '';
                      $showFooter = in_array($courseName, ['LATIHAN INDUSTRI', 'LATIHAN PRAKTIKAL']);
                  }
              }
              @endphp
              <div class="box-footer" {{ $showFooter ? '' : 'hidden' }}>
                <div class="pull-right">
                    <button id="savebtn" class="btn btn-primary"><i class="ti-trash"></i> Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
        <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
    var selected_group = "";
    var selected_final = "{{ request()->final }}";

    $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
    

    $(document).on('change', '#group', function(e) {
        selected_group = $(e.target).val();

        getGroup(selected_group);
    });

    function getGroup(group)
    {

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/final/' . request()->id . '/' . request()->final . '/getGroup') }}",
            method   : 'POST',
            data 	 : {group: group},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
              if(data.message == 'success')
              {
              $('#myTable').DataTable().destroy();
              $('#myTable').html(data.content);
              $('#myTable').DataTable({
                dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
              });
              }

            }
        });

    }


$('#savebtn').click(function (e){

  e.preventDefault();   

  var marks = $("input[name='marks[]']").map(function(){return $(this).val();}).get();;

  var ics = $("input[name='ic[]']").map(function(){return $(this).val();}).get();;

  var finalid = "{{ request()->final }}";

  //alert(finalid);

  var formData = new FormData();

  var alldata = [];


  formData.append('marks', JSON.stringify(marks));

  formData.append('ics', JSON.stringify(ics));

  formData.append('finalid', JSON.parse(JSON.stringify(finalid)));

  $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/lecturer/final/update') }}',
        type: 'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        error:function(err){
            console.log(err);
        },
        success:function(res){
            try{
                if(res.message == "Success"){
                    alert("Success! final's Results has been updated/created!");
                    window.location.reload();
                }else{
                    if(res.message == "Field Error"){ 
                          //$('#'+f+'_error').html(res.error[f]);
                          alert("The Marks you inputted has exceed the maximum percentage! Please recheck.");
                    }
                    else if(res.message == "Group code already existed inside the system"){
                        $('#classcode_error').html(res.message);
                    }
                    else{
                        alert(res.message);
                    }
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });

})

</script>
@stop