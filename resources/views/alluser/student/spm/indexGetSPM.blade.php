<h3>SPM</h3>
<table id="table_dismissed" class="w-100 table table-bordered display margin-top-10 w-p100 table-layout: fixed;" style="width: 100%;">
    <thead>
        <tr>
            <th style="text-align: center">Bil</th>
            <th style="text-align: center">Student Name</th>
            <th style="text-align: center">No. Matric</th>
            <th style="text-align: center">Status</th>
            <th style="text-align: center">Session</th>
            <th style="text-align: center">Semester</th>
            <th style="text-align: center" colspan="20">SPM RESULT</th>
            <th style="text-align: center">TOTAL CREDIT</th>
            <th style="text-align: center">TOTAL GRADE</th>
            <th style="text-align: center">TOTAL GRADE OVERALL</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['student'] as $key => $std)
        <tr>
            <td style="text-align: center">{{ $key+1 }}</td>
            <td style="text-align: center">{{ $std->name }}</td>
            <td style="text-align: center">{{ $std->no_matric }}</td>
            <td style="text-align: center">{{ $std->status }}</td>
            <td style="text-align: center">{{ $std->SessionName }}</td>
            <td style="text-align: center">{{ $std->semester }}</td>
            @foreach($data['spm'][$key] as $keys => $spm)
            @if($spm)
            <td style="text-align: center">{{ $spm->subject }}</td>
            <td style="text-align: center">{{ $spm->grade }}</td>
            @else
            <td style="text-align: center"> </td>
            <td style="text-align: center"> </td>
            @endif
            @endforeach
            <td style="text-align: center">{{ $data['result'][$key] }}</td>
            <td style="text-align: center">{{ $data['total_grade'][$key] }}</td>
            <td style="text-align: center">{{ $data['total_grade_overall'][$key] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>SPMV</h3>
<table id="table_spmv" class="w-100 table table-bordered display margin-top-10 w-p100 table-layout: fixed;" style="width: 100%;">
  <thead>
      <tr>
          <th style="text-align: center">Bil</th>
          <th style="text-align: center">Student Name</th>
          <th style="text-align: center">No. Matric</th>
          <th style="text-align: center">Year</th>
          <th style="text-align: center">Turn No</th>
          <th style="text-align: center">Cert Type</th>
          <th style="text-align: center">PNGKA</th>
          <th style="text-align: center">PNGKV</th>
          <th style="text-align: center">BMKV</th>
          <th style="text-align: center">Sejarah</th>
      </tr>
  </thead>
  <tbody>
      @foreach($data['student'] as $key => $std)
      @if($data['spmv'][$key])
      <tr>
          <td style="text-align: center">{{ $key+1 }}</td>
          <td style="text-align: center">{{ $std->name }}</td>
          <td style="text-align: center">{{ $std->no_matric }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->year }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->number_turn }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->cert_type }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->pngka }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->pngkv }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->bmkv }}</td>
          <td style="text-align: center">{{ $data['spmv'][$key]->sejarahspm }}</td>
      </tr>
      @endif
      @endforeach
  </tbody>
</table>

<h3>SKM</h3>
<table id="table_skm" class="w-100 table table-bordered display margin-top-10 w-p100 table-layout: fixed;" style="width: 100%;">
  <thead>
      <tr>
          <th style="text-align: center">Bil</th>
          <th style="text-align: center">Student Name</th>
          <th style="text-align: center">No. Matric</th>
          <th style="text-align: center">Level 3</th>
          <th style="text-align: center">Field</th>
          <th style="text-align: center">Program</th>
      </tr>
  </thead>
  <tbody>
      @foreach($data['student'] as $key => $std)
      @if($data['skm'][$key])
      <tr>
          <td style="text-align: center">{{ $key+1 }}</td>
          <td style="text-align: center">{{ $std->name }}</td>
          <td style="text-align: center">{{ $std->no_matric }}</td>
          <td style="text-align: center">{{ $data['skm'][$key]->tahap3 == 1 ? 'yes' : 'no' }}</td>
          <td style="text-align: center">{{ $data['skm'][$key]->in_field == 0 ? 'In Field' : 'Public' }}</td>
          <td style="text-align: center">{{ $data['skm'][$key]->program }}</td>
      </tr>
      @endif
      @endforeach
  </tbody>
</table>

<script>
  $(document).ready(function () {
    // Initialize DataTable
    $('#table_dismissed').DataTable({
      dom: 'lBfrtip',
      paging: false,
      buttons: [
        {
          text: 'Excel',
          action: function () {
            // Get the HTML tables
            const table1 = document.getElementById("table_dismissed");
            const table2 = document.getElementById("table_spmv");
            const table3 = document.getElementById("table_skm");

            // Create a new Workbook object
            const wb = XLSX.utils.book_new();

            // Combine all tables into a single sheet
            const combinedSheet = XLSX.utils.aoa_to_sheet([]);

            // Function to append table data to the sheet
            function appendTableToSheet(table, startingRow) {
              const sheetData = XLSX.utils.table_to_sheet(table, { raw: true });
              XLSX.utils.sheet_add_aoa(combinedSheet, XLSX.utils.sheet_to_json(sheetData, { header: 1 }), { origin: `A${startingRow}` });
            }

            // Append the first table
            appendTableToSheet(table1, 1);

            // Calculate the last row of table1 and append table2 starting below it
            const rowCountTable1 = table1.rows.length + 2; // Add some spacing
            appendTableToSheet(table2, rowCountTable1);

            // Calculate the last row of table2 and append table3 starting below it
            const rowCountTable2 = rowCountTable1 + table2.rows.length + 2;
            appendTableToSheet(table3, rowCountTable2);

            // Add the combined sheet to the Workbook
            XLSX.utils.book_append_sheet(wb, combinedSheet, "CombinedSheet");

            // Trigger the download of the Excel file
            XLSX.writeFile(wb, "combined-tables.xlsx");
          }
        }
      ],
    });

    // Merging Cells Logic
    let db = document.getElementById("table_dismissed");
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

    // Remove hidden cells
    $("#table_dismissed td:first-child:hidden").remove();
  });
</script>

  
  <script type="text/javascript">
  
  
   window.onload = mergeCells;
   
  
  </script>