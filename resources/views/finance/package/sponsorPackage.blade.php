@extends('../layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Sponsorship</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item" aria-current="page">Package</li>
                <li class="breadcrumb-item active" aria-current="page">Sponsorship</li>
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
                <h3 class="card-title">Create Sponsorship</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                      <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                      <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="student">Student</label>
                        <select class="form-select" id="student" name="student">
                          <option value="-" selected disabled>-</option>
                        </select>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6" id="program-card">
                    <div class="form-group">
                      <label class="form-label" for="package">Package PTPTN</label>
                      <select class="form-select" id="package" name="package">
                        <option value="" selected disabled>-</option>
                        @foreach ($data['package'] as $pkg)
                          <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>       
                  <div class="col-md-6" id="intake-card">
                    <div class="form-group">
                      <label class="form-label" for="method">Payment Method</label>
                      <select class="form-select" id="method" name="method">
                        <option value="" selected>-</option>
                        @foreach ($data['method'] as $mth)
                          <option value="{{ $mth->id }}">{{ $mth->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6" id="claim-card">
                    <div class="form-group">
                      <label class="form-label" for="amount">Amount (RM)</label>
                      <input type="number" id="amount" name="amount" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                      <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Submit</button>
                    </div>
                  </div>
                </div>
                <hr>
                <div id="add-student-div">
                </div>
              </div>
              <!-- /.card-body -->
              <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <!-- modal content-->
                  <div class="modal-content" id="getModal">
                    <div class="card-body p-0">
                      <div id="program_list">

                      </div>
                    </div>
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

  var getInput = [];

  $(document).ready(function(){

    getIncentive();

  });

  $('#search').keyup(function(){

    getStudent($(this).val());

  });

  function getStudent(search)
  {

      return $.ajax({
              headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
              url      : "{{ url('pendaftar/student/status/listStudent') }}",
              method   : 'POST',
              data 	 : {search: search},
              error:function(err){
                  alert("Error");
                  console.log(err);
              },
              success  : function(data){
                  $('#student').html(data);
                  $('#student').selectpicker('refresh');

              }
          });
      
  }

  function getIncentive()
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/package/sponsorPackage/getsponsorPackage') }}",
            method   : 'GET',
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#add-student-div').html(data);

            }
        });
  }

  function submit()
  {

    var formData = new FormData();

    getInput = {
      student : $('#student').val(),
      package : $('#package').val(),
      method : $('#method').val(),
      amount : $('#amount').val()
    };
    
    formData.append('formData', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/package/sponsorPackage/storeSponsorPackage') }}",
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
                    alert("Success! Package has been added/created!")
                    getIncentive();
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

  function getEditPackage(id)
  {

    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('/finance/package/sponsorPackage/getEditPackage') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            $('#program_list').html(data);
            $('#uploadModal').modal('show');
        }
    });

  }

  function Register(prg,id)
  {

    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('/finance/package/incentive/registerPRG') }}",
        method   : 'POST',
        data 	 : {prg: prg,id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            getProgram(data)
        }
    });

  }

  function unRegister(prg,id)
  {

    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('/finance/package/incentive/unregisterPRG') }}",
        method   : 'POST',
        data 	 : {prg: prg,id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            getProgram(data)
        }
    });

  }

  function update(id)
  {

    var formData = new FormData();

    getInput = {
      id : id,
      package : $('#package2').val(),
      method : $('#method2').val(),
      amount : $('#amount2').val()
    };
    
    formData.append('formData', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/package/sponsorPackage/updateSponsorPackage') }}",
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
                    alert("Success! Package has been updated!")
                    getIncentive();
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

  function deletePackage(id)
  {

    Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
    }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('finance/package/sponsorPackage/deleteSponsorPackage') }}",
                  method   : 'DELETE',
                  data 	 : {id: id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      alert(data.message);
                      getIncentive();
                  }
              });
          }
      });

  }

</script>
@endsection
