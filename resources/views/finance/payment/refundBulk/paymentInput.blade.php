@extends('../layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Bulk Refund Payment</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item" aria-current="page">Payment</li>
              <li class="breadcrumb-item active" aria-current="page">Bulk Refund Payment</li>
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
                <h3 class="card-title">Bulk Refund Payment</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Refund Details</b>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="total">Total Amount</label>
                        <input type="number" class="form-control" id="total" name="total" step="0.01">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="remark">Remark (Optional)</label>
                        <input type="text" class="form-control" id="remark" name="remark">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="form-group mt-3">
                          <button type="submit" class="btn btn-primary mb-3 pull-right" onclick="add()">Add</button>
                        </div>
                    </div>
                  </div>
                  <div id="form-method">
              
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

function add()
{

  var forminput = [];
  var formData = new FormData();

  forminput = {
    total: $('#total').val(),
    remark: $('#remark').val()
  };

  formData.append('paymentData', JSON.stringify(forminput));

  $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/finance/payment/refund/bulk/input/store') }}',
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
                if(typeof res.message !== 'undefined'){
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
                }else{
                    alert("Success! Bulk Refund Payment Details has been added!");
                    $('#form-method').html(res);
                    document.getElementById('confirm-card').hidden = false;
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });


}

// Add event listener for Enter key only on payment method fields
$(document).ready(function() {
    $(document).on('keydown', '#method, #bank, #nodoc, #amount', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            var mainid = $('#mainid').val();
            if(mainid) {
                add2(mainid);
            }
        }
    });
});

function add2(id)
{

  var forminput = [];
  var formData = new FormData();

  forminput = {
    id: id,
    method: $('#method').val(),
    bank: $('#bank').val(),
    nodoc: $('#nodoc').val(),
    amount: $('#amount').val()
  };

  formData.append('paymentData', JSON.stringify(forminput));

  $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/finance/payment/refund/bulk/input/store2') }}',
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
                      alert("Success! Payment Method Details has been added!");
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
                  url      : "{{ url('finance/payment/refund/bulk/input/delete') }}",
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

  var id = $('#mainid').val();

  if(id != '')
  {

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('finance/payment/refund/bulk/input/confirm') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
          alert("Success! Bulk Refund Payment has been confirmed!");
            window.location.href = "/finance/payment/refund/bulk";

        }
    });

    }else{

      alert('Please submit & fill refund details first!')

    }

}



</script>
@endsection

