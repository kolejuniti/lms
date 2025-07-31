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
                <h3 class="card-title">Student Discount Management</h3>
              </div>
              <!-- /.card-header -->
              
              <!-- Student Search Section -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Add New Discount Entry</b>
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
                  <div id="discount-form-section" style="display: none;">
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <h5>Enter Discount Information</h5>
                        <hr>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="discount_date">Date</label>
                          <input type="date" class="form-control" id="discount_date" name="discount_date" value="{{ date('Y-m-d') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="discount_percentage">Discount (%)</label>
                          <input type="number" step="0.01" class="form-control" id="discount_percentage" name="discount_percentage" min="0" max="100">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label" for="total_arrears">Jumlah Tunggakan (RM)</label>
                          <input type="number" step="0.01" class="form-control" id="total_arrears" name="total_arrears" min="0">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label" for="received_discount">Terimaan Diskaun (RM)</label>
                          <input type="number" step="0.01" class="form-control" id="received_discount" name="received_discount" min="0">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label" for="payment">Bayaran Pelajar (RM)</label>
                          <input type="number" step="0.01" class="form-control" id="payment" name="payment" min="0">
                        </div>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <button type="button" class="btn btn-success" onclick="saveDiscountData()">Save Discount Data</button>
                        <button type="button" class="btn btn-secondary" onclick="clearForm()">Clear Form</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Monthly/Yearly Data Table Section -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Discount Records - Monthly/Yearly View</b>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="filter_year">Filter by Year</label>
                        <select class="form-select" id="filter_year" name="filter_year">
                          <option value="all" selected>All Years</option>
                          @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                          @endfor
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="filter_month">Filter by Month</label>
                        <select class="form-select" id="filter_month" name="filter_month">
                          <option value="all" selected>All Months</option>
                          @for($month = 1; $month <= 12; $month++)
                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                          @endfor
                        </select>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary mb-3" onclick="loadDiscountRecords()">Load Records</button>
                  
                  <div id="discount-records-table">
                    <!-- Table will be loaded here -->
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

// Student search functionality
$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
});

$('#student').on('change', function(){
    var selectedStudent = $(this).val();
    if(selectedStudent !== '-') {
        $('#discount-form-section').show();
    } else {
        $('#discount-form-section').hide();
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

function saveDiscountData()
{
    var studentIc = $('#student').val();
    var discountDate = $('#discount_date').val();
    var discountPercentage = $('#discount_percentage').val();
    var totalArrears = $('#total_arrears').val();
    var receivedDiscount = $('#received_discount').val();
    var payment = $('#payment').val();

    // Validation
    if(!studentIc || studentIc === '-') {
        alert('Please select a student');
        return;
    }

    if(!discountDate || !discountPercentage || !totalArrears || !receivedDiscount || !payment) {
        alert('Please fill in all discount information fields including date');
        return;
    }

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/discountReport/saveDiscountData') }}",
            method   : 'POST',
            data 	 : {
                student_ic: studentIc,
                date: discountDate,
                discount: discountPercentage,
                total_arrears: totalArrears,
                received_discount: receivedDiscount,
                payment: payment
            },
            error:function(err){
                alert("Error saving data");
                console.log(err);
            },
            success  : function(data){
                if(data.message === 'Success') {
                    alert('Discount data saved successfully!');
                    clearForm();
                    loadDiscountRecords(); // Reload records
                } else {
                    alert('Error: ' + data.message);
                }
            }
        });
}

function clearForm()
{
    $('#search').val('');
    $('#student').html('<option value="-" selected disabled>-</option>');
    $('#discount_date').val('{{ date('Y-m-d') }}');
    $('#discount_percentage').val('');
    $('#total_arrears').val('');
    $('#received_discount').val('');
    $('#payment').val('');
    $('#discount-form-section').hide();
}

function loadDiscountRecords()
{
    var year = $('#filter_year').val();
    var month = $('#filter_month').val();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/discountReport/getDiscountRecords') }}",
            method   : 'POST',
            data 	 : {
                year: year,
                month: month
            },
            error:function(err){
                alert("Error loading records");
                console.log(err);
            },
            success  : function(data){
                $('#discount-records-table').html(data);
                
                // Initialize DataTable if records exist
                if($('#discount_records_table').length) {
                    $('#discount_records_table').DataTable({
                        dom: 'lBfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],// Order by created date descending
                    });
                }
            }
        });
}

// Load records on page load
$(document).ready(function(){
    loadDiscountRecords();
});

</script>
@endsection 