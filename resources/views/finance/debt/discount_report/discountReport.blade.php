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
        <h4 class="page-title">Discount Report</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Discount Report</li>
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
                <h3 class="card-title">Student Discount Report</h3>
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
                      <input type="date" class="form-control" id="from" name="from" />
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="form-label" for="to">TO</label>
                        <input type="date" class="form-control" id="to" name="to" />
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

  if (!from || !to) {
    alert('Please select both From and To dates');
    return;
  }

  // Show the spinner
  $('#loading-spinner').css('display', 'block');

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/discountReport/getDiscountReport') }}",
            method   : 'POST',
            data 	 : {program: program, from: from, to: to},
            error:function(err){
                alert("Error");
                console.log(err);

                // Hide the spinner on error
                $('#loading-spinner').css('display', 'none');
            },
            success  : function(data){
                // Hide the spinner on success
                $('#loading-spinner').css('display', 'none');

                $('#form-student').html(data);

                $('#discount_table').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print'
                  ],
                });

               

                      
            }
        });



}




</script>
@endsection 