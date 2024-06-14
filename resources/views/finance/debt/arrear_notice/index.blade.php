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

@if(session('success'))
<script>
  alert('{{ session("success") }}')
</script>
@endif

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Generate Arrears Notice</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Generate Arrears Notice</li>
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
                <h3 class="card-title">Generate Arrears Notice</h3>
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
                    </div>
                    <form action="{{ route('finance.arrearNotice.store') }}" method="POST" target="_blank">
                    @csrf
                      <div class="row">
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
                        <div class="col-md-6">
                          <div class="form-group">
                          <label class="form-label" for="money">Monthly Installment (RM)</label>
                          <input type="number" class="form-control" step="1" placeholder="Value" id="money" name="money" value="{{ old('money') }}"/>
                          @error('name')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                          <label class="form-label" for="period">Period</label>
                          <input type="number" class="form-control" placeholder="Month" id="period" name="period" value="{{ old('period') }}"/>
                          @error('period')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                          <label class="form-label" for="period">Start Date</label>
                          <input type="date" class="form-control" id="start" name="start" value="{{ old('start') }}"/>
                          @error('start')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                          </div>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary pull-right mb-3" >Generate</button>       
                    </form>    
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

$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
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

function generateNotice() {

  var program = $('#program').val();
  var from = $('#from').val();
  var to = $('#to').val();

  // Show the spinner
  $('#loading-spinner').css('display', 'block');

  return $.ajax({
    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
    url: "{{ url('finance/debt/monthlyPayment/getMonthlyPayment') }}",
    method: 'POST',
    data: {program: program, from: from, to: to},
    error: function(err){
      alert("Error");
      console.log(err);
      // Hide the spinner on error
      $('#loading-spinner').css('display', 'none');
    },
    success: function(data){
      // Hide the spinner on success
      $('#loading-spinner').css('display', 'none');

      $('#form-student').html(data);
      $('#voucher_table').DataTable({
        dom: 'lBfrtip',
        paging: false,
        buttons: [
          {
            text: 'Excel',
            action: function () {
              // get the HTML table to export
              const table = document.getElementById("voucher_table");
              // create a new Workbook object
              const wb = XLSX.utils.book_new();
              // add a new worksheet to the Workbook object
              const ws = XLSX.utils.table_to_sheet(table);
              XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
              // trigger the download of the Excel file
              XLSX.writeFile(wb, "exported-data.xlsx");
            }
          }
        ],
      });
    }
  });
  
}



</script>
@endsection
