@extends('../layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Payment</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item" aria-current="page">Package</li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
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
                <h3 class="card-title">Create Payment</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6" id="ptptn-card">
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
                      <label class="form-label" for="type">Payment Type</label>
                      <select class="form-select" id="type" name="type">
                        <option value="" selected>-</option>
                        @foreach ($data['type'] as $mth)
                          <option value="{{ $mth->id }}">{{ $mth->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="col-md-6" id="semester-card">
                  <div class="form-group">
                    <label class="form-label" for="sem1">Semester 1</label>
                    <input type="number" id="sem1" name="sem1" step="0.01" value="0.00" class="form-control">
                  </div>
                </div>
                <br>
                <div class="col-md-6" id="semester-card">
                  <div class="form-group">
                    <label class="form-label" for="sem2">Semester 2</label>
                    <input type="number" id="sem2" name="sem2" step="0.01" value="0.00" class="form-control">
                  </div>
                </div>
                <br>
                <div class="col-md-6" id="semester-card">
                  <div class="form-group">
                    <label class="form-label" for="sem3">Semester 3</label>
                    <input type="number" id="sem3" name="sem3" step="0.01" value="0.00" class="form-control">
                  </div>
                </div>
                <br>
                <div class="col-md-6" id="semester-card">
                  <div class="form-group">
                    <label class="form-label" for="sem4">Semester 4</label>
                    <input type="number" id="sem4" name="sem4" step="0.01" value="0.00" class="form-control">
                  </div>
                </div>
                <br>
                <div class="col-md-6" id="semester-card">
                  <div class="form-group">
                    <label class="form-label" for="sem5">Semester 5</label>
                    <input type="number" id="sem5" name="sem5" step="0.01" value="0.00" class="form-control">
                  </div>
                </div>
                <br>
                <div class="col-md-6" id="semester-card">
                  <div class="form-group">
                    <label class="form-label" for="sem6">Semester 6</label>
                    <input type="number" id="sem6" name="sem6" step="0.01" value="0.00" class="form-control">
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
              <div id="uploadModal" class="modal fade" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <!-- modal content-->
                  <div class="modal-content" id="getModal">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body">
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

    getPayment();

  });

  function getPayment()
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/package/payment/getPayment') }}",
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
      package : $('#package').val(),
      type : $('#type').val(),
      sem1 : $('#sem1').val(),
      sem2 : $('#sem2').val(),
      sem3 : $('#sem3').val(),
      sem4 : $('#sem4').val(),
      sem5 : $('#sem5').val(),
      sem6 : $('#sem6').val()
    };
    
    formData.append('formData', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/package/payment/storePaymentPKG') }}",
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
                    alert("Success! Payment Package has been added/created!")
                    getPayment();
                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
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

function getProgram(id)
{

  return $.ajax({
      headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
      url      : "{{ url('/finance/package/payment/getProgramPayment') }}",
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

  var intake = $('#intake').val();

  if(intake != null)
  {

    return $.ajax({
      headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
      url      : "{{ url('/finance/package/payment/registerPRGPYM') }}",
      method   : 'POST',
      data 	 : {prg: prg, id: id, intake: intake},
      error:function(err){
          alert("Error");
          console.log(err);
      },
      success  : function(data){
          getProgram(data)
      }
    });

  }else{

    alert('Please select Intake!');

  }

}

function deleteReg(prg,id)
{

  return $.ajax({
      headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
      url      : "{{ url('/finance/package/payment/deletePRGPYM') }}",
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

</script>
@endsection
