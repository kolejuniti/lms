@extends('layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Status Aging Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Status Aging Report</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
  

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Filters</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
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
          {{-- <div class="row">
            <div class="col-md-6">
              <div class="form-group">
              <label class="form-label" for="year">FROM</label>
              <input type="number" class="form-control" min="1900" max="2099" step="1" placeholder="year" id="year" name="year" />
              </div>
            </div>
          </div> --}}
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="from">FROM</label>
                <input type="date" class="form-control" id="from" name="from">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="name">TO</label>
                <input type="date" class="form-control" id="to" name="to">
                </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button>

        </div>
        <div class="card-body p-0" style="overflow-x: auto;">
          <table id="complex_header" class="w-100 table table-bordered display margin-top-10 w-p100">
            <thead>
              <tr>
                  <th>
                    Program
                  </th>
              </tr>
            </thead>
            <tbody id="table">
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

{{-- @if(session('newStud'))
    <script>
      alert('Success! Student has been registered!')
      window.open('/pendaftar/surat_tawaran?ic={{ session("newStud") }}')
    </script>
@endif --}}

<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteMaterial(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/pendaftar/delete') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }

</script>


  <script type="text/javascript">

    function submit()
    {

      var forminput = [];
      var formData = new FormData();

      forminput = {
        // year: $('#year').val(),
        from: $('#from').val(),
        to: $('#to').val(),
        status: $('#status').val(),
      };


      formData.append('filtersData', JSON.stringify(forminput));

      $('#complex_header').DataTable().destroy();

      $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url: '{{ url('/finance/report/statusAgingReport/getStatusAgingReport') }}',
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
                      $('#complex_header').html(res.data);

                      $('#complex_header').DataTable({
                        dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                        paging: false,

                        buttons: [
                            {
                              text: 'Excel',
                              action: function () {
                                // get the HTML table to export
                                const table = document.getElementById("complex_header");
                                
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

                      let db = document.getElementById("complex_header");
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
                      $("#complex_header td:first-child:hidden").remove();
                      
                  }else{
                      $('.error-field').html('');
                      if(res.message == "Field Error"){
                          for (f in res.error) {
                              $('#'+f+'_error').html(res.error[f]);
                          }
                      }
                      else if(res.message == "Group code already existed inside the system"){
                          $('#classcode_error').html(res.message);
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
  </script>
@endsection
