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

  .ctos-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
  }

  .ctos-toolbar .form-group {
    margin-bottom: 0;
  }

  .ctos-actions {
    margin-left: auto;
    display: flex;
    gap: 8px;
  }

  .ctos-section {
    margin-top: 16px;
  }

  .ctos-table-card {
    margin-top: 12px;
  }

  .ctos-table-card .card-header {
    padding: 12px 16px;
  }

  .ctos-table-card .card-body {
    padding: 12px 16px 16px;
  }

  .ctos-table {
    margin-bottom: 0;
    font-size: 0.95rem;
    line-height: 1.4;
  }

  .ctos-table thead th,
  .ctos-table tbody td {
    vertical-align: middle;
    padding: 8px 10px;
  }

  .ctos-table thead th {
    background: #f5f7fa;
    font-weight: 600;
  }

  .ctos-table tbody tr:nth-child(even) {
    background: #fbfcfe;
  }

  .ctos-table tbody tr:hover {
    background: #f0f4ff;
  }

  .ctos-col-num {
    text-align: center;
    font-variant-numeric: tabular-nums;
  }

  .ctos-action-cell {
    min-width: 220px;
  }

  .ctos-action-row {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .ctos-action-row .form-control {
    min-width: 150px;
  }

  @media (max-width: 768px) {
    .ctos-actions {
      width: 100%;
      justify-content: flex-start;
    }

    .ctos-action-row {
      flex-direction: column;
      align-items: stretch;
    }
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Student CTOS</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Student CTOS</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif


    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Student CTOS</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-body">
                  <div class="ctos-toolbar">
                    <div class="col-md-5 col-sm-12">
                      <div class="form-group">
                        <label class="form-label" for="excelFile">File Import</label>
                        <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls" />
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-group">
                        <label class="form-label" for="statusFilter">Status Pelajar</label>
                        <select class="form-control" id="statusFilter">
                          <option value="">All Status</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="form-group">
                        <label class="form-label" for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" />
                      </div>
                    </div>
                    <div class="ctos-actions">
                      <button class="btn btn-info" id="importButton">Import</button>
                      <button type="submit" class="btn btn-success" onclick="exportToExcel()">Export</button>
                    </div>
                  </div>
                  <div class="row ctos-section">
                    <div id="form-student" class="col-12">
                      <!-- form start -->
                      <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div class="form-group mt-3">
                                    <div class="card ctos-table-card">
                                      <div class="card-header">
                                        <label class="form-label mb-0">Students CTOS</label>
                                      </div>
                                      <div class="card-body">
                                        <div class="table-responsive">
                                        <table class="table table-bordered display margin-top-10 ctos-table" id="ctos_list" style="width: 100%;">
                                            <thead id="voucher_list">
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th class="ctos-col-num">
                                                        No. KP / Passport
                                                    </th>
                                                    <th class="ctos-col-num">
                                                        No. Matric
                                                    </th>
                                                    <th>
                                                        Program
                                                    </th>
                                                    <th>
                                                        Status Pelajar
                                                    </th>
                                                    <th>
                                                        Status
                                                    </th>
                                                    <th>
                                                        Date CTOS
                                                    </th>
                                                    <th>
                                                        Added By
                                                    </th>
                                                    <th>
                                                      Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="table1">
                                            @foreach($data['CTOS'] as $key => $ctos)
                                            <tr>
                                              <td>
                                                  {{ $key+1 }}
                                              </td>
                                              <td>
                                                  {{ $ctos->name }}
                                              </td>
                                              <td class="ctos-col-num">
                                                  {{ $ctos->ic }}
                                              </td>
                                              <td class="ctos-col-num">
                                                  {{ $ctos->no_matric }}
                                              </td>
                                              <td>
                                                  {{ $ctos->progcode }}
                                              </td>
                                              <td>
                                                  {{ $ctos->status_student }}
                                              </td>
                                              <td>
                                                  CTOS
                                              </td>
                                              <td>
                                                  {{ $ctos->date_ctos }}
                                              </td>
                                              <td>
                                                  {{ $ctos->addBy }}
                                              </td>
                                              <td class="ctos-action-cell">
                                                <div class="ctos-action-row">
                                                  <input type="date" class="form-control" id="date-{{ $ctos->id }}" name="date-{{ $ctos->id }}" />
                                                  <button type="submit" class="btn btn-primary" onclick="releaseStudent('{{ $ctos->id }}')">Release</button>
                                                </div>
                                              </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                
                                            </tfoot>
                                        </table>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                          <div class="col-md-12 mt-3">
                              <div class="form-group mt-3">
                                  <div class="card ctos-table-card">
                                    <div class="card-header">
                                      <label class="form-label mb-0">Students CTOS Release</label>
                                    </div>
                                    <div class="card-body">
                                      <div class="table-responsive">
                                      <table class="w-100 table table-bordered display margin-top-10 w-p100 ctos-table" id="ctos_list2">
                                      <thead id="voucher_list">
                                          <tr>
                                              <th style="width: 1%">
                                                  No.
                                              </th>
                                              <th>
                                                  Name
                                              </th>
                                              <th class="ctos-col-num">
                                                  No. KP / Passport
                                              </th>
                                              <th class="ctos-col-num">
                                                  No. Matric
                                              </th>
                                              <th>
                                                  Program
                                              </th>
                                              <th>
                                                    Status Pelajar
                                              </th>
                                              <th>
                                                  Status
                                              </th>
                                              <th>
                                                  Date CTOS Release
                                              </th>
                                              <th>
                                                  Added By
                                              </th>
                                              <th>
                                                  Action
                                              </th>
                                          </tr>
                                      </thead>
                                      <tbody id="table2">
                                      @foreach($data['CTOSRelease'] as $key => $ctos)
                                      <tr>
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            {{ $ctos->name }}
                                        </td>
                                        <td class="ctos-col-num">
                                            {{ $ctos->ic }}
                                        </td>
                                        <td class="ctos-col-num">
                                            {{ $ctos->no_matric }}
                                        </td>
                                        <td>
                                            {{ $ctos->progcode }}
                                        </td>
                                        <td>
                                            {{ $ctos->status_student }}
                                        </td>
                                        <td>
                                            Released
                                        </td>
                                        <td>
                                            {{ $ctos->date_release }}
                                        </td>
                                        <td>
                                            {{ $ctos->addBy }}
                                        </td>
                                        <td class="ctos-action-cell" style="text-align: center">
                                          <button type="submit" class="btn btn-danger" onclick="deleteCTOS('{{ $ctos->id }}')">Delete</button>
                                        </td>
                                      </tr>
                                      @endforeach    
                                      </tbody>
                                      <tfoot>
                                          
                                      </tfoot>
                                  </table>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script type="text/javascript">

let ctosTable = null;
let ctosReleaseTable = null;

$(document).ready(function() {
    ctosTable = $('#ctos_list').DataTable({
        dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });

    ctosReleaseTable = $('#ctos_list2').DataTable({
        dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });

    setupStatusFilter();
});

function setupStatusFilter() {
    const statusSelect = $('#statusFilter');
    const currentValue = statusSelect.val();

    const statuses = new Set();
    if (ctosTable) {
        ctosTable.column(5).data().each(function(value) {
            if (value) statuses.add(value.toString().trim());
        });
    }
    if (ctosReleaseTable) {
        ctosReleaseTable.column(5).data().each(function(value) {
            if (value) statuses.add(value.toString().trim());
        });
    }

    const sorted = Array.from(statuses).sort();
    statusSelect.empty().append('<option value="">All Status</option>');
    sorted.forEach(function(status) {
        statusSelect.append(`<option value="${status}">${status}</option>`);
    });

    if (currentValue) {
        statusSelect.val(currentValue);
    }

    statusSelect.off('change.ctos').on('change.ctos', function() {
        const val = $(this).val();
        const search = val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '';

        if (ctosTable) {
            ctosTable.column(5).search(search, true, false).draw();
        }
        if (ctosReleaseTable) {
            ctosReleaseTable.column(5).search(search, true, false).draw();
        }
    });

    if (currentValue) {
        statusSelect.trigger('change');
    }
}

function updateCTOSTables(data) {
    // Destroy the existing DataTables instances
    $('#ctos_list').DataTable().destroy();
    $('#ctos_list2').DataTable().destroy();

    // Clear the existing table rows
    $('#table1').empty();
    $('#table2').empty();

    // Loop through the new data and append rows for CTOS
    $.each(data.CTOS, function(key, ctos) {
        $('#table1').append(`
            <tr>
                <td>${key + 1}</td>
                <td>${ctos.name}</td>
                <td class="ctos-col-num">${ctos.ic}</td>
                <td class="ctos-col-num">${ctos.no_matric}</td>
                <td>${ctos.progcode}</td>
                <td>${ctos.status_student}</td>
                <td>CTOS</td>
                <td>${ctos.date_ctos}</td>
                <td>${ctos.addBy}</td>
                <td class="ctos-action-cell">
                    <div class="ctos-action-row">
                        <input type="date" class="form-control" id="date-${ctos.id}" name="date-${ctos.id}" />
                        <button type="button" class="btn btn-primary" onclick="releaseStudent('${ctos.id}')">Release</button>
                    </div>
                </td>
            </tr>
        `);
    });

    // Initialize the DataTable for #ctos_list again
    ctosTable = $('#ctos_list').DataTable({
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });

    // Loop through the new data and append rows for CTOSRelease
    $.each(data.CTOSRelease, function(key, ctos2) {
        $('#table2').append(`
            <tr>
                <td>${key + 1}</td>
                <td>${ctos2.name}</td>
                <td class="ctos-col-num">${ctos2.ic}</td>
                <td class="ctos-col-num">${ctos2.no_matric}</td>
                <td>${ctos2.progcode}</td>
                <td>${ctos2.status_student}</td>
                <td>Released CTOS</td>
                <td>${ctos2.date_release}</td>
                <td>${ctos2.addBy}</td>
                <td class="ctos-action-cell" style="text-align: center">
                  <button type="button" class="btn btn-danger" onclick="deleteCTOS('${ctos2.id}')">Delete</button>
                </td>
            </tr>
        `);
    });

    // Initialize the DataTable for #ctos_list2 again
    ctosReleaseTable = $('#ctos_list2').DataTable({
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });

    setupStatusFilter();
}


function exportToExcel() {
    // Data to be exported
    const data = [
        { student_ic: '' }
    ];

    // Convert data to worksheet
    const ws = XLSX.utils.json_to_sheet(data);

    // Create a new workbook
    const wb = XLSX.utils.book_new();

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Students');

    // Generate Excel file and trigger download
    XLSX.writeFile(wb, 'students.xlsx');
}

function releaseStudent(id)
{

  var data = $('#date-'+id).val();

  return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/debt/studentCtos/releaseCTOS') }}",
            method   : 'POST',
            data 	 : {id: id, date: data},
            success  : function(response){
                alert(response.success);
                
                // Call the function to update the tables
                updateCTOSTables(response.data);
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message; // Show the server error message
                }
                alert('Error - ' + errorMessage);
            }
        });

}

$('#importButton').on('click', function() {
    var fileInput = $('#excelFile')[0];
    if (fileInput.files.length === 0) {
        alert('Please select a file.');
        return;
    }

    var forminput = [];

    forminput = {
        date: $('#date').val()
    };

    var formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('data', JSON.stringify(forminput));

    $.ajax({
        url: "{{ route('finance.studentCtos.importCtos') }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          alert(response.success);

          // Call the function to update the tables
          updateCTOSTables(response.data);
        },
        error: function(xhr, status, error) {
            let errorMessage = xhr.status + ': ' + xhr.statusText;
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message; // Show the server error message
            }
            alert('Error - ' + errorMessage);
        }
    });

});

function deleteCTOS(id)
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
                  url      : "{{ url('finance/debt/studentCtos/deleteCTOS') }}",
                  method   : 'POST',
                  data 	 : {id: id},
                  success  : function(response){
                    alert(response.success);

                    // Call the function to update the tables
                    updateCTOSTables(response.data);
                  },
                  error: function(xhr, status, error) {
                      let errorMessage = xhr.status + ': ' + xhr.statusText;
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message; // Show the server error message
                      }
                      alert('Error - ' + errorMessage);
                  }
              });
          }
      });

  }
</script>
@endsection
