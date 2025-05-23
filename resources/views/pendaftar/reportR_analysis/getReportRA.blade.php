<div class="row mt-3 d-flex">
  <div class="col-md-12 mb-3">
    <div class="pull-right">
      <button id="exportBtn" class="btn btn-success">Export to Excel</button>
    </div>
  </div>
</div>

@if(isset($data['tableLabels']) && is_array($data['tableLabels']))
  <!-- Multiple Tables Display -->
  @foreach($data['tableLabels'] as $key => $label)
  <div class="card mb-3" id="stud_info_{{ $key }}">
    <div class="card-header">
      <b>{{ $label }}</b>
    </div>
    <div class="small text-muted px-3 py-2">
        Note: Data shown follows the calendar date (Sunday to Saturday)
    </div>
    <div class="card-body p-0">
      <table class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">Total Student R</th>
                <th style="width: 15%">Total by Convert</th>
                <th style="width: 15%">Balance Student</th>
                <th style="width: 15%">Student Active</th>
                <th style="width: 15%">Student Rejected</th>
                <th style="width: 15%">Student Offered</th>
                <th style="width: 15%">Student KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i></th>
                <th style="width: 15%">Student Others <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)"></i></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $data['allStudents'][$key] }}</td>
            <td>{{ $data['totalConvert'][$key] }}</td>
            <td>{{ $data['total'][$key]->total_ - $data['totalConvert'][$key] }}</td>
            <td>{{ $data['registered'][$key] }}</td>
            <td>{{ $data['rejected'][$key] }}</td>
            <td>{{ $data['offered'][$key] }}</td>
            <td>{{ $data['KIV'][$key] }}</td>
            <td>{{ $data['others'][$key] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endforeach

  @if(count($data['tableLabels']) > 1)
  <!-- Summary Table for Multiple Tables -->
  <div class="card mb-3" id="summary_table">
    <div class="card-header">
      <b>Summary of All Tables</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">Total Student R</th>
                <th style="width: 15%">Total by Convert</th>
                <th style="width: 15%">Balance Student</th>
                <th style="width: 15%">Student Active</th>
                <th style="width: 15%">Student Rejected</th>
                <th style="width: 15%">Student Offered</th>
                <th style="width: 15%">Student KIV</th>
                <th style="width: 15%">Student Others</th>
            </tr>
        </thead>
        <tbody>
          <tr>
            @php
            $total_all = array_sum($data['allStudents']);
            $total_convert = array_sum($data['totalConvert']);
            $total_registered = array_sum($data['registered']);
            $total_rejected = array_sum($data['rejected']);
            $total_offered = array_sum($data['offered']);
            $total_kiv = array_sum($data['KIV']);
            $total_others = array_sum($data['others']);
            $grand_total = $total_convert + $total_registered + $total_rejected + $total_offered + $total_kiv + $total_others;
            @endphp
            <td>{{ $total_all }}</td>
            <td>{{ $total_convert }}</td>
            <td>{{ $grand_total - $total_convert }}</td>
            <td>{{ $total_registered }}</td>
            <td>{{ $total_rejected }}</td>
            <td>{{ $total_offered }}</td>
            <td>{{ $total_kiv }}</td>
            <td>{{ $total_others }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endif
@else
  <!-- Single Table Display -->
  <div class="card mb-3" id="stud_info">
    <div class="card-header">
      <b>Total Student R Analysis</b>
    </div>
    <div class="small text-muted px-3 py-2">
        Note: Data shown follows the calendar date (Sunday to Saturday)
    </div>  
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">Total Student R</th>
                <th style="width: 15%">Total by Convert</th>
                <th style="width: 15%">Balance Student</th>
                <th style="width: 15%">Student Active</th>
                <th style="width: 15%">Student Rejected</th>
                <th style="width: 15%">Student Offered</th>
                <th style="width: 15%">Student KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i></th>
                <th style="width: 15%">Student Others <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)"></i></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            @php
            $total_all = $data['allStudents'] + $data['totalConvert'] + $data['registered'] + $data['rejected'] + $data['offered'] + $data['KIV'] + $data['others'];
            @endphp
            <td>{{ $data['allStudents'] }}</td>
            <td>{{ $data['totalConvert'] }}</td>
            <td>{{ $total_all - $data['totalConvert'] }}</td>
            <td>{{ $data['registered'] }}</td>
            <td>{{ $data['rejected'] }}</td>
            <td>{{ $data['offered'] }}</td>
            <td>{{ $data['KIV'] }}</td>
            <td>{{ $data['others'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endif

<script>
  $(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#exportBtn').on('click', function(e) {
      e.preventDefault();
      printReport2();
    });
  });

  function printReport2() {
    // Check if using multiple tables or single range
    const tableCount = parseInt($('#table_count').val());
    
    if (tableCount > 0) {
      // Use multiple date ranges for export
      const dateRanges = collectDateRanges();
      
      var url = "{{ url('pendaftar/student/reportRA/getStudentReportRA?excel=true') }}";
      var form = document.createElement('form');
      form.method = 'POST';
      form.action = url;
      
      var csrfInput = document.createElement('input');
      csrfInput.type = 'hidden';
      csrfInput.name = '_token';
      csrfInput.value = $('meta[name="csrf-token"]').attr('content');
      form.appendChild(csrfInput);
      
      var dateRangesInput = document.createElement('input');
      dateRangesInput.type = 'hidden';
      dateRangesInput.name = 'date_ranges';
      dateRangesInput.value = JSON.stringify(dateRanges);
      form.appendChild(dateRangesInput);
      
      var multipleTablesInput = document.createElement('input');
      multipleTablesInput.type = 'hidden';
      multipleTablesInput.name = 'multiple_tables';
      multipleTablesInput.value = 'true';
      form.appendChild(multipleTablesInput);
      
      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
    } else {
      // Use original single range method
      var from = $('#from').val();
      var to = $('#to').val();
      var url = "{{ url('pendaftar/student/reportRA/getStudentReportRA?excel=true') }}";

      window.location.href = `${url}&from=${from}&to=${to}`;
    }
  }

  // Helper function to collect date ranges (should be available from parent page)
  function collectDateRanges() {
    const ranges = [];
    const tableCount = parseInt($('#table_count').val());
    
    for (let i = 1; i <= tableCount; i++) {
      const fromValue = $(`#from_${i}`).val();
      const toValue = $(`#to_${i}`).val();
      
      if (fromValue && toValue) {
        ranges.push({
          table: i,
          from: fromValue,
          to: toValue
        });
      }
    }
    
    return ranges;
  }
</script>