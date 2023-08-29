@extends('../layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Claim Package</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item" aria-current="page">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Group</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      @if($errors->any())
      <a class="btn btn-danger btn-sm ml-2 ">
        <i class="ti-na">
        </i>
        {{$errors->first()}}
      </a>
      @endif
    </div>
  

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Claim Package</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="form-group">
                    <b>Search Claim Package</b>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6" id="program-card">
                    <div class="form-group">
                      <label class="form-label" for="program">Program</label>
                      <select class="form-select" id="program" name="program">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['program'] as $prg)
                          <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>       
                  <div class="col-md-6" id="intake-card">
                    <div class="form-group">
                      <label class="form-label" for="intake">Intake Session</label>
                      <select class="form-select" id="intake" name="intake">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['session'] as $ses)
                        <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6" id="semester-card">
                    <div class="form-group">
                      <label class="form-label" for="semester">Semester</label>
                      <select class="form-select" id="semester" name="semester">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['semester'] as $ses)
                        <option value="{{ $ses->id }}">{{ $ses->semester_name}}</option> 
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="form-group">
                    <b>Copy Claim Package</b><br>
                    *Please select refered semester from above and select new semester in this section to copy.
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6" id="semester2-card">
                    <div class="form-group">
                      <label class="form-label" for="semester2">Semester</label>
                      <select class="form-select" id="semester2" name="semester2">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['semester'] as $ses)
                        <option value="{{ $ses->id }}">{{ $ses->semester_name}}</option> 
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                      <button type="submit" class="btn btn-primary pull-right mb-3" onclick="copy()">Copy</button>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="form-group">
                    <b>Add Claim Package</b>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6" id="claim-card">
                    <div class="form-group">
                      <label class="form-label" for="claim">Claim Type</label>
                      <select class="form-select" id="claim" name="claim">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['claim'] as $clm)
                        <option value="{{ $clm->id }}">{{ $clm->name}}</option> 
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6" id="claim-card">
                    <div class="form-group">
                      <label class="form-label" for="price">Price per Unit (RM)</label>
                      <input type="number" id="price" name="price" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                      <button type="submit" class="btn btn-primary pull-right mb-3" onclick="add()">Add</button>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-12 mt-3">
                      <div class="form-group mt-3">
                          <label class="form-label">Claim Package List</label>
                          <div id="add-student-div"></div>
                      </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- modal content-->
                    <div class="modal-content" id="getModal">
                    </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">

  var selected_program = 0;
  var selected_session = 0;
  var selected_semester = 0;
  var getInput = [];

  $(document).on('change', '#program', async function(e){
    selected_program = $(e.target).val();

    await getClaim(selected_program,selected_session,selected_semester);

  });

  $(document).on('change', '#intake', async function(e){
    selected_session = $(e.target).val();

    await getClaim(selected_program,selected_session,selected_semester);
  });

  $(document).on('change', '#semester', async function(e){
    selected_semester = $(e.target).val();

    await getClaim(selected_program,selected_session,selected_semester);
  });

  function getClaim(program,session,semester)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/claimpackage/getclaim') }}",
            method   : 'POST',
            data 	 : {program: program,
                      session: session,
                      semester: semester},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#add-student-div').html(data);
                $('#add-student-div').selectpicker('refresh');

            }
        });
  }

  document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      event.preventDefault();
      add(); // Call your add() function or form submission logic
    }
  });

  function add()
  {

    var formData = new FormData();

    getInput = {
      program : $('#program').val(),
      intake : $('#intake').val(),
      semester : $('#semester').val(),
      claim : $('#claim').val(),
      price : $('#price').val(),
    };
    
    formData.append('addClaim', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/claimpackage/addclaim') }}",
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
                    alert("Success! Claim has been added/created!");
                    
                    // Start with an empty table structure
                    var newTable = "<table id='table_projectprogress_course' class='table table-striped projects display dataTable no-footer' style='width: 100%;'>" +
                                        "<thead class='thead-themed'>" +
                                        "<tr>" +
                                            "<th style='width: 1%'>No.</th>" +
                                            "<th style='width: 20%'>Claim Name</th>" +
                                            "<th style='width: 5%'>Price Per Unit</th>" +
                                            "<th style='width: 5%'>Program</th>" +
                                            "<th style='width: 5%'>Intake</th>" +
                                            "<th style='width: 5%'>Semester</th>" +
                                            "<th style='width: 20%'></th>" +
                                        "</tr>" +
                                        "</thead>" +
                                        "<tbody>";

                    // Add new rows
                    $.each(res.data, function(i, item) {
                        var newRow = "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + item.name + "</td>" +
                            "<td>" + item.pricePerUnit + "</td>" +
                            "<td>" + item.progname + "</td>" +
                            "<td>" + item.SessionName + "</td>" +
                            "<td>" + item.semester_name + "</td>" +
                            "<td class='project-actions text-right' style='text-align: center;'>" +
                              "<a class='btn btn-info btn-sm pr-2' href='#' onclick='updatePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-pencil-alt'></i> Edit" +
                              "</a>" +
                              "<a class='btn btn-danger btn-sm' href='#' onclick='deletePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-trash'></i> Delete" +
                              "</a>" +
                            "</td>" +
                        "</tr>";
                        newTable += newRow;
                    });

                    // Close table structure
                    newTable += "</tbody></table>";

                    // Replace the div contents with the new table
                    $('#add-student-div').html(newTable);

                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
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

  }


  function copy()
  {

    var formData = new FormData();

    getInput = {
      program : $('#program').val(),
      intake : $('#intake').val(),
      semester : $('#semester').val(),
      semester2 : $('#semester2').val()
    };
    
    formData.append('copyClaim', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/claimpackage/copyclaim') }}",
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
                  alert("Success! Claim has been copied!");
                    
                    // Start with an empty table structure
                    var newTable = "<table id='table_projectprogress_course' class='table table-striped projects display dataTable no-footer' style='width: 100%;'>" +
                                        "<thead class='thead-themed'>" +
                                        "<tr>" +
                                            "<th style='width: 1%'>No.</th>" +
                                            "<th style='width: 20%'>Claim Name</th>" +
                                            "<th style='width: 5%'>Price Per Unit</th>" +
                                            "<th style='width: 5%'>Program</th>" +
                                            "<th style='width: 5%'>Intake</th>" +
                                            "<th style='width: 5%'>Semester</th>" +
                                            "<th style='width: 20%'></th>" +
                                        "</tr>" +
                                        "</thead>" +
                                        "<tbody>";

                    // Add new rows
                    $.each(res.data, function(i, item) {
                        var newRow = "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + item.name + "</td>" +
                            "<td>" + item.pricePerUnit + "</td>" +
                            "<td>" + item.progname + "</td>" +
                            "<td>" + item.SessionName + "</td>" +
                            "<td>" + item.semester_name + "</td>" +
                            "<td class='project-actions text-right' style='text-align: center;'>" +
                              "<a class='btn btn-info btn-sm pr-2' href='#' onclick='updatePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-pencil-alt'></i> Edit" +
                              "</a>" +
                              "<a class='btn btn-danger btn-sm' href='#' onclick='deletePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-trash'></i> Delete" +
                              "</a>" +
                            "</td>" +
                        "</tr>";
                        newTable += newRow;
                    });

                    // Close table structure
                    newTable += "</tbody></table>";

                    // Replace the div contents with the new table
                    $('#add-student-div').html(newTable);

                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
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

  }


  function updatePackage(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/claimpackage/update') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal').html(data);
                $('#uploadModal').modal('show');
            }
        });

  }

  function deletePackage(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('finance/claimpackage/delete') }}",
                  method   : 'POST',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(res){
                    alert("Success! Claim has been deleted!");
                    
                    // Start with an empty table structure
                    var newTable = "<table id='table_projectprogress_course' class='table table-striped projects display dataTable no-footer' style='width: 100%;'>" +
                                        "<thead class='thead-themed'>" +
                                        "<tr>" +
                                            "<th style='width: 1%'>No.</th>" +
                                            "<th style='width: 20%'>Claim Name</th>" +
                                            "<th style='width: 5%'>Price Per Unit</th>" +
                                            "<th style='width: 5%'>Program</th>" +
                                            "<th style='width: 5%'>Intake</th>" +
                                            "<th style='width: 5%'>Semester</th>" +
                                            "<th style='width: 20%'></th>" +
                                        "</tr>" +
                                        "</thead>" +
                                        "<tbody>";

                    // Add new rows
                    $.each(res.data, function(i, item) {
                        var newRow = "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + item.name + "</td>" +
                            "<td>" + item.pricePerUnit + "</td>" +
                            "<td>" + item.progname + "</td>" +
                            "<td>" + item.SessionName + "</td>" +
                            "<td>" + item.semester_name + "</td>" +
                            "<td class='project-actions text-right' style='text-align: center;'>" +
                              "<a class='btn btn-info btn-sm pr-2' href='#' onclick='updatePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-pencil-alt'></i> Edit" +
                              "</a>" +
                              "<a class='btn btn-danger btn-sm' href='#' onclick='deletePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-trash'></i> Delete" +
                              "</a>" +
                            "</td>" +
                        "</tr>";
                        newTable += newRow;
                    });

                    // Close table structure
                    newTable += "</tbody></table>";

                    // Replace the div contents with the new table
                    $('#add-student-div').html(newTable);
                  }
              });
          }
      });
  }

</script>
@endsection
