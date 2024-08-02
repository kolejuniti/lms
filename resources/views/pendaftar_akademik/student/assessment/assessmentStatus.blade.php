
@extends('layouts.pendaftar_akademik')

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
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Assessment</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Assessment</li>
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
                <h3 class="card-title">Assessment List</h3>
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
                              @foreach ($data['group'] as $grp)
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
                                  Submission Date
                                </th>
                                <th>
                                  Marks
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($data['assessment'] as $key => $qz)

                              @if ($data['status'][$key]->final_mark != 0)
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
                                @if ($data['status'][$key]->final_mark != 0)
                                  
                                  <td>
                                        {{ $data['status'][$key]->submittime}}
                                  </td>                                               
                                @else
                                <td>
                                  -
                                </td>
                                @endif
                                <td>
                                  <div class="form-inline col-md-6 d-flex">
                                      <input  type="number" class="form-control" name="marks[]" max="{{ $qz->total_mark}}" value="{{ $data['status'][$key]->final_mark }}">
                                      <input  type="text" name="ic[]" value="{{ $qz->student_ic }}" hidden>
                                      <span>{{ $data['status'][$key]->final_mark }} / {{ $qz->total_mark}}</span>
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
              <div class="box-footer">
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
    var selected_quiz = "{{ request()->quiz }}";

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
            url      : "{{ url('lecturer/quiz2/' . request()->id . '/' . request()->quiz . '/getGroup') }}",
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

  var assessmentid = "{{ $data['id'] }}";

  //alert(quizid);

  var formData = new FormData();

  var alldata = [];


  formData.append('marks', JSON.stringify(marks));

  formData.append('ics', JSON.stringify(ics));

  formData.append('assessmentid', JSON.parse(JSON.stringify(assessmentid)));

  $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('/AR/student/studentAssessment/assessmentStatus/' . $data['id'] . '/' . $data['type'] . '/update') }}",
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
                    alert("Success! Assessment's Results has been updated/created!");
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

function deleteQuiz(id){     
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
@stop