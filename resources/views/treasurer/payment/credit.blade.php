@extends('../layouts.treasurer')

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
              <li class="breadcrumb-item" aria-current="page">Extra</li>
              <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
                <h3 class="card-title">Credit Note</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Student</b>
                </div>
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
                  <div id="form-student">
              
                  </div>
                  <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <!-- modal content-->
                      <div class="modal-content" id="getModal">
                        <label class="form-label">Student Due List</label>
                        <table id="statement_list" class="w-100 table table-bordered display margin-top-10 w-p100">

                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="confirm-card" hidden>
                    <div class="col-md-12 mt-3 text-center">
                        <div class="form-group mt-3">
                          <button type="submit" class="btn btn-primary mb-3" onclick="confirm()">Confirm</button>
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

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">

$('#search').keyup(function(){

    getStudent($(this).val());

});

$('#student').on('change', function(){

getStudent = $(this).val();

getStudInfo(getStudent);

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

function getStudInfo(student)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('/treasurer/payment/credit/getStudent') }}",
            method   : 'POST',
            data 	 : {student: student},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){

                $('#form-student').html(data);
            
            }
        });
}

function save(ic)
{

  var forminput = [];
  var formData = new FormData();

  forminput = {
    ic: ic,
    discount: $('input[name="discount"]:checked').val(),
    remark: $('#remark').val()
  };

  formData.append('paymentData', JSON.stringify(forminput));

  $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/treasurer/payment/credit/storeCredit') }}',
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
                    alert("Success! Payment Details has been added!");
                    $('#idpayment').val(res.data);
                    $('#payment_list').html(res.claim);
                    $('#payment_list').DataTable();
                    document.getElementById('confirm-card').hidden = false;
                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
                    }
                    else if(res.message == "Please fill all required field!"){
                        alert(res.message);
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



function confirm()
{

  var id = $('#idpayment').val();

  var forminput = [];
    var formData = new FormData();

    var input = [];
    var input2 = [];

    forminput = {
      id: id
    };

    formData.append('paymentDetail', JSON.stringify(forminput));
    
    $('input[id="phyid[]"]').each(function() {
      input.push({
            id : $(this).val()
        });
    });

    $('input[id="payment[]"]').each(function() {
      input2.push({
            payment : $(this).val()
        });
    });

    formData.append('paymentinput', JSON.stringify(input));
    formData.append('paymentinput2', JSON.stringify(input2));

    $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url: '{{ url('/treasurer/payment/credit/confirmCredit') }}',
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
                      alert("Success! Credit Details has been added!");
                      window.location.reload();
                  }else{
                      $('.error-field').html('');
                      if(res.message == "Field Error"){
                          for (f in res.error) {
                              $('#'+f+'_error').html(res.error[f]);
                          }
                      }
                      else if(res.message == "Please fill all required field!"){
                          alert(res.message);
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

function getStatement(ic)
{

  return $.ajax({
      headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
      url      : "{{ url('/treasurer/payment/credit/getStatement') }}",
      method   : 'POST',
      data 	 : {ic: ic},
      error:function(err){
          alert("Error");
          console.log(err);
      },
      success  : function(data){
          $('#statement_list').html(data);
          $('#statement_list').DataTable();
          $('#uploadModal').modal('show');
      }
  });

}


</script>
@endsection
