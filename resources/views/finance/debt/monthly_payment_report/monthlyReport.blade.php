@extends('../layouts.finance')

@section('main')

<style>
  .horizontal-line {
    position: relative;
    text-align: center;
    margin-top: 20px;
  }

  .line {
    display: inline-block;
    width: 45%; /* Adjust the width of the line */
    border-top: 1px solid #000; /* Adjust the color and style of the line */
  }

  .or {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff; /* Adjust the background color to match your background */
    padding: 0 10px;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Monthly Payment Report</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Monthly Payment Report</li>
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
                <h3 class="card-title">Student Monthly Payment Report</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Student</b>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12 ml-3">
                      <div class="form-group">
                          <label class="form-label" for="program">Program</label>
                          <select class="form-select" id="program" name="program">
                            <option value="all" selected>All Program</option> 
                            @foreach ($data['program'] as $prg)
                            <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option> 
                            @endforeach
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                      <label class="form-label" for="from">FROM</label>
                      <input type="number" class="form-control" min="1900" max="2099" step="1" placeholder="year" id="from" name="from" />
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="form-label" for="name">TO</label>
                        <input type="number" class="form-control" min="1900" max="2099" step="1" placeholder="year" id="to" name="to" />
                        </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button>
                  <div class="row">
                    <div id="form-student">
                      
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


function submit()
{

  var program = $('#program').val();
  var from = $('#from').val();
  var to = $('#to').val();

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/monthlyPayment/getMonthlyPayment') }}",
            method   : 'POST',
            data 	 : {program: program, from: from, to: to},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
              // var d = new Date();

              // var month = d.getMonth()+1;
              // var day = d.getDate();

              // var output = d.getFullYear() + '/' +
              //     (month<10 ? '0' : '') + month + '/' +
              //     (day<10 ? '0' : '') + day;

                $('#form-student').html(data);
                $('#voucher_table').DataTable();
            }
        });



}


function add(ic)
{

  var forminput = [];
  var formData = new FormData();

  forminput = {
    ic: $('#ic').val(),
    from: $('#from').val(),
    to: $('#to').val(),
    amount: $('#amount').val(),
    expired: $('#expired').val(),
  };

  formData.append('voucherDetail', JSON.stringify(forminput));

  $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/finance/voucher/student/storeVoucherDtl') }}',
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

                    $('#voucher_table').html(res.data);

                    if (res.exists && res.exists.length > 0) {
                        var existingVouchers = res.exists.join(', ');
                        alert('These vouchers already exist: ' + existingVouchers);
                    }
                    
                    $('#voucher_table').DataTable();
                    
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

function deletedtl(id)
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
                  url      : "{{ url('finance/voucher/student/deleteVoucherDtl') }}",
                  method   : 'POST',
                  data 	 : {id: id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      alert("success");
                      $('#voucher_table').html(data);
                      $('#voucher_table').DataTable();
                  }
              });
          }
      });

}

function claimVoucher(id)
{

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/voucher/student/claimVoucherDtl') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                alert("claim success!");
                $('#voucher_table').html(data);
                $('#voucher_table').DataTable();

            }
        });

}

function unclaimVoucher(id)
{

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/voucher/student/unclaimVoucherDtl') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                alert("unclaim success");
                $('#voucher_table').html(data);
                $('#voucher_table').DataTable();

            }
        });

}

function confirm()
{

  var id = $('#idpayment').val();

  $.ajax({
      headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
      url      : "{{ url('finance/payment/confirmPayment') }}",
      method   : 'POST',
      data 	 : {id: id},
      error:function(err){
          alert("Error");
          console.log(err);
      },
      success  : function(data){
        try{
          if(data.message == "Success")
          {
            alert(data.message);
            if(data.alert != null)
            {
              alert(data.alert);
            }

            window.open('/finance/sponsorship/payment/getReceipt?id=' + data.id, '_blank');
            window.location.reload();
          }else{
            alert(data.message);
          }
        }catch(err){
            alert("Ops sorry, there is an error");
        }
        
      }
  });

}


</script>
@endsection
