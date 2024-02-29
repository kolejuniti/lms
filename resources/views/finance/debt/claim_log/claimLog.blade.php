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
        <h4 class="page-title">Payment Claim Log</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Payment Claim Log</li>
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
                <h3 class="card-title">Student Payment Claim Log</h3>
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
                  <div class="horizontal-line">
                    <div class="line"></div>
                    <div class="or">or</div>
                    <div class="line"></div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-md-6 ml-3">
                      <div class="form-group">
                          <label class="form-label" for="program">Program</label>
                          <select class="form-select" id="program" name="program">
                            <option value="all" selected>All Program</option> 
                            @foreach ($data['program'] as $prg)
                            <option value="{{ $prg->id }}">{{ $prg->progcode}} - {{ $prg->progname}}</option> 
                            @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="col-md-6 mr-3" id="status-card">
                      <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                          <option value="all" selected>All Status</option> 
                          @foreach ($data['status'] as $sts)
                          <option value="{{ $sts->id }}">{{ $sts->name}}</option> 
                          @endforeach
                        </select>
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

$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
});

$('#student').on('change', function(){
    var selectedStudent = $(this).val();
    getStudInfo(selectedStudent);
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
            url      : "{{ url('finance/debt/claimLog/getClaimLog') }}",
            method   : 'POST',
            data 	 : {student: student},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#form-student').html(data);
                $('#voucher_table').DataTable();
            }
        });
}

function submit()
{

  var program = $('#program').val();
  var status = $('#status').val();

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/claimLog/getClaimLog') }}",
            method   : 'POST',
            data 	 : {program: program, status: status},
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
                $('#voucher_table').DataTable({
                        dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
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

                      let db = document.getElementById("voucher_table");
                      let dbRows = db.rows;
                      let lastValue = "";
                      let lastCounter = 1;
                      let lastRow = 0;
                      for (let i = 0; i < dbRows.length; i++) {
                        let thisValue = dbRows[i].cells[0].innerHTML;
                        if (thisValue == lastValue) {
                          lastCounter++;
                          dbRows[lastRow].cells[0].rowSpan = lastCounter;
                          dbRows[i].cells[0].style.display = "none";
                        } else {
                          dbRows[i].cells[0].style.display = "table-cell";
                          lastValue = thisValue;
                          lastCounter = 1;
                          lastRow = i;
                        }
                      }

                      // Remove the cells that are hidden
                      $("#voucher_table td:first-child:hidden").remove();
            }
        });



}


</script>
@endsection
