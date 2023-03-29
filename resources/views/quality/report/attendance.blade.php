@extends('../layouts.pendaftar')

@section('main')
<style>
  @media print {

  @page {size: A4 landscape;max-height:100%; max-width:100%}

  /* use width if in portrait (use the smaller size to try 
    and prevent image from overflowing page... */
  img { height: 90%; margin: 0; padding: 0; }

  body{width:100%;
  height:100%;
  -webkit-transform: rotate(-90deg) scale(.68,.68); 
  -moz-transform:rotate(-90deg) scale(.58,.58) }    }
</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Lecturer Attendance</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item" aria-current="page">Report</li>
              <li class="breadcrumb-item active" aria-current="page">Attendance</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div id="printableArea">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lecturer Attendance</h3>
                <a type="button" class="waves-effect waves-light btn btn-primary btn-sm" onclick="printDiv('printableArea')">
                  <i class="ti-printer"></i>&nbsp Print
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Find Lecturer</b>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="faculty">Faculty</label>
                        <select class="form-select" id="faculty" name="faculty" required>
                          <option value="-" selected disabled>-</option>
                            @foreach ($data['faculty'] as $fcl)
                            <option value="{{ $fcl->id }}">{{ $fcl->facultyname }}</option> 
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="session">Session</label>
                        <select class="form-select" id="session" name="session" required>
                          <option value="-" selected disabled>-</option>
                            @foreach ($data['session'] as $ses)
                            <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="form-student">
              
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">

var faculty;
var session;

$('#faculty').on('change', function(){
    faculty = $(this).val();
    getAttendance(faculty,session);
    //alert(faculty);
});

$('#session').on('change', function(){
    session = $(this).val();
    getAttendance(faculty,session);
});

function getAttendance(faculty,session)
{

        return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('quality/report/attendance/getLecturer') }}",
            method   : 'POST',
            data 	 : {faculty: faculty, session: session},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
              $('#form-student').html(data);

              
}




function add(ic)
{
  if($('#idpayment').val() != '')
  {

    var forminput = [];
    var formData = new FormData();

    forminput = {
      id: $('#idpayment').val(),
      method: $('#method').val(),
      bank: $('#bank').val(),
      nodoc: $('#nodoc').val(),
      amount: $('#amount').val(),
    };

    formData.append('paymentDetail', JSON.stringify(forminput));

    $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url: '{{ url('/finance/payment/tuition/storeTuitionDtl') }}',
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
                      $('#payment_list').html(res.data);
                      $('#payment_list').DataTable();
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

  }else{

    alert('Please save payment details first!');

  }


}

function deletedtl(dtl,id)
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
                  url      : "{{ url('finance/payment/tuition/deleteTuition') }}",
                  method   : 'POST',
                  data 	 : {dtl:dtl, id: id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      alert("success");
                      $('#payment_list').html(data);
                      $('#payment_list').DataTable();
                  }
              });
          }
      });

}


function confirm()
{

  var id = $('#idpayment').val();
  var sum = $('#sum').val();
  var sum2 = $('#sum2').val();

  if(sum != '' && sum2 != '')
  {

    if(parseInt(sum2) == parseInt(sum))
    {

    var forminput = [];
    var formData = new FormData();

    var input = [];
    var input2 = [];

    forminput = {
      id: id,
      sum: sum,
      sum2: sum2,
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
          url: '{{ url('/finance/payment/tuition/confirmTuition') }}',
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
                      window.open('/finance/sponsorship/payment/getReceipt?id=' + res.id, '_blank');
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

    }else{

      alert('Please make sure total of payment method equal to total of student due list!')

    }

  }else{

    alert('Please submit & fill payment details first!');

  }

}

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

</script>
@endsection
