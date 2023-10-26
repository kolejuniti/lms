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
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready( function () {
        $('#table_dismissed').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          paging: false,
  
          buttons: [
              {
                text: 'Excel',
                action: function () {
                  // get the HTML table to export
                  const table = document.getElementById("table_dismissed");
                  
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
  
        // Remove the cells that are hidden
        $("#table_dismissed td:first-child:hidden").remove();
          } );
  </script>
  
  <script type="text/javascript">
  
  
   window.onload = mergeCells;
   
  
  </script>