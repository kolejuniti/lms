@extends('layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Debt & Payment Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Debt & Payment Report</li>
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
          <div class="row">
            <div class="col-md-6 ml-3">
              <div class="form-group">
                  <label class="form-label" for="program">Program</label>
                  <select class="form-select" id="program" name="program">
                    <option value="all" selected>All Program</option> 
                    @foreach ($data['program'] as $prg)
                    <option value="{{ $prg->id }}">{{ $prg->progname}}</option> 
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="col-md-6 mr-3" id="status-card">
              <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                  <option value="-" selected disabled>-</option>
               
                </select>
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
                  </th>
                  <th colspan="3">
                      A Tuntutan
                  </th>
                  <th colspan="16">
                      B Diskaun Pengajian
                  </th>
                  <th colspan="3">
                      C Pengurangan Yuran
                  </th>
                  <th>
                      D
                  </th>
                  <th >
                    A-(B+C+D)
                  </th>
                </tr>
                <tr>
                    <th>
                        Program
                    </th>
                    <th>
                        Yuran Pengajian (RM)
                    </th>
                    <th>
                        Nota Debit (RM) 
                    </th>
                    <th>
                        Nota Kredit (RM)
                    </th>
                    <th>
                        Insentif Naik Semester (RM)
                    </th>
                    <th>
                        Insentif Pendidikan iNED (RM)
                    </th>
                    <th>
                        UNITI Fund (RM)
                    </th>
                    <th>
                        Biasiswa (RM)
                    </th>
                    <th>
                        Uniti Education Fund (RM)
                    </th>
                    <th>
                        Diskaun Covid-19/Frontliners (RM)
                    </th>
                    <th>
                        Insentif MCO 3.0 (RM)
                    </th>
                    <th>
                        Insentif Khas Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Khas B40 Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Khas M40 Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Khas T20 Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Khas Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Rahmah B40 Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Rahmah M40 Kolej UNITI (RM)
                    </th>
                    <th>
                        Tabung Rahmah T20 Kolej UNITI (RM)
                    </th>
                    <th>
                        Rabung Rahmah Kolej UNITI (RM)
                    </th>
                    <th>
                        Nota Kredit (RM)
                    </th>
                    <th>
                        Penerimaan Kaunter (RM)
                    </th>
                    <th>
                        Bayaran Penaja (RM)
                    </th>
                    <th>
                        Bayaran Lebihan (RM)
                    </th>
                    <th>
                        Baki Tunggakan Yuran (RM)
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            {{-- @foreach ($student as $key=> $stud)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <td style="width: 15%">
                  {{ $stud->name }}
                </td>
                <td style="width: 15%">
                  {{ $stud->ic }}
                </td>
                <td style="width: 10%">
                  {{ $stud->no_matric }}
                </td>
                <td>
                  {{ $stud->program }}
                </td>
                <td class="project-actions text-right" >
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="/pendaftar/edit/{{ $stud->ic }}">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="getProgram('{{ $stud->ic }}')">
                    <i class="ti-pencil-alt">
                    </i>
                    Program History
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $stud->ic }}')">
                      <i class="ti-trash">
                      </i>
                      Delete
                  </a>
                </td>
              </tr>
            @endforeach --}}
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div id="uploadModal" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
              <!-- modal content-->
              <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                </div>
                <div class="modal-body" id="getModal">
                  
                </div>
              </div>
          </div>
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

<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>

  <script type="text/javascript">

    function submit()
    {

      var forminput = [];
      var formData = new FormData();

      forminput = {
        from: $('#from').val(),
        to: $('#to').val(),
        program: $('#program').val(),
        semester: $('#semester').val(),
      };


      formData.append('filtersData', JSON.stringify(forminput));

       $('#complex_header').DataTable().destroy();

      $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url: '{{ url('/finance/report/arrearsReport/getArrearsReport') }}',
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
               
                      alert("Success! Status & Student info has been updated!")
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
                      
                
              }catch(err){
                  alert("Ops sorry, there is an error");
              }
          }
      });

    }
  </script>
@endsection
