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
        <h4 class="page-title">CTOS Report</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">CTOS Report</li>
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
                <h3 class="card-title">Student CTOS Report</h3>
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

  // Show the spinner
  $('#loading-spinner').css('display', 'block');

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/ctosReport/getCtosReport') }}",
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
                                
                                // format cells with leading zeros as text
                                const range = XLSX.utils.decode_range(ws['!ref']);
                                for (let row = range.s.r; row <= range.e.r; ++row) {
                                    for (let col = range.s.c; col <= range.e.c; ++col) {
                                        const cell = ws[XLSX.utils.encode_cell({r: row, c: col})];
                                        if (cell && typeof cell.v === 'string' && cell.v.match(/^0\d+$/)) {
                                            cell.t = 's';
                                        }
                                    }
                                }
                                
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
