@extends('../layouts.coop')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Voucher</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Voucher</li>
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
                <h3 class="card-title">Student Voucher</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Voucher</b>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                          <label class="form-label" for="name">No. Voucher</label>
                          <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                          </div>
                      </div>
                  </div>
                  <div class="row" id="confirm-card" >
                    <div class="col-md-12 mt-3 d-flex justify-content-end">
                        <div class="form-group mt-3">
                          <button type="submit" class="btn btn-primary mb-3" onclick="confirm()">Find</button>
                        </div>
                    </div>       
                  </div>
                  <div class="card">
                    <div id="form-voucher">
              
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

function confirm()
{

  var search = $('#search').val();

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('coop/voucher/findVoucher') }}",
            method   : 'POST',
            data 	 : {search: search},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){

                if(data.message)
                {

                  alert('Voucher does not exist or not registered, please check again!');

                }else{

                  $('#form-voucher').html(data);

                }
                
            }
        });

}


function redeem(id)
{

  let date = $('#r_date').val();

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('coop/voucher/redeemVoucher') }}",
            method   : 'POST',
            data 	 : {id: id, date: date},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){

                if(data.message)
                {

                  alert('Voucher does not exist or not registered, please check again!');

                }else{

                  $('#form-voucher').html(data);

                }
                
            }
        });

}



function add(ic)
{
  if($('#idpayment').val() != '')
  {

    var forminput = [];
    var formData = new FormData();

    forminput = {
      id: $('#idpayment').val(),
      type: $('#type').val(),
      method: $('#method').val(),
      bank: $('#bank').val(),
      nodoc: $('#nodoc').val(),
      amount: $('#amount').val(),
    };

    formData.append('paymentDetail', JSON.stringify(forminput));

    $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url: '{{ url('/finance/payment/storePaymentDtl') }}',
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

function deletedtl(dtl,meth,id)
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
                  url      : "{{ url('finance/payment/deletePayment') }}",
                  method   : 'POST',
                  data 	 : {dtl:dtl, meth: meth, id: id},
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


</script>
@endsection
