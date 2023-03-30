<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Attenance List</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <table id="table_dismissed" class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th>
                                        Lecturer
                                    </th>
                                    <th>
                                        Subject
                                    </th>
                                    <th>
                                        Attendance Record
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['lecturer'] as $key => $lct)
                                <tr>
                                    <td>
                                    {{ $lct->name }}
                                    </td>
                                    <td>
                                    @if(count($data['attendance'][$key]) > 0)
                                    <a class="btn btn-success btn-sm mr-2">{{ $lct->course }} ({{ $lct->code }})</a>
                                    @else
                                    <a class="btn btn-danger btn-sm mr-2">{{ $lct->course }} ({{ $lct->code }})</a>
                                    @endif
                                    </td>
                                    <td>
                                    @foreach($data['attendance'][$key] as $att)
                                    <a class="btn btn-info btn-sm mr-2 mb-2">Group {{ $att->groupname }} ({{ $att->classdate }})</a>
                                    @endforeach
                                    </td>
                                </tr>
                                @endforeach
                                <tfoot>
                                </tfoot> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tableTo2DArray(table) {
            const rows = table.querySelectorAll('tr');
            const data = [];
            rows.forEach((row, rowIndex) => {
                const rowData = [];
                if (rowIndex === 0) {
                    row.querySelectorAll('th').forEach((cell) => {
                        rowData.push(cell.textContent.trim());
                    });
                } else {
                    row.querySelectorAll('td').forEach((cell, cellIndex) => {
                        if (cellIndex === 2) {
                            const groups = cell.querySelectorAll('a');
                            groups.forEach((group, groupIndex) => {
                                rowData.push(group.textContent.trim());
                            });
                        } else {
                            rowData.push(cell.textContent.trim());
                        }
                    });
                }
                if (rowData.length > 0) {
                    data.push(rowData);
                }
            });
            return data;
        }

        $(document).ready(function () {
            $('#table_dismissed').DataTable({
                dom: 'lBfrtip',
                paging: false,
                buttons: [
                    {
                        text: 'Excel',
                        action: function () {
                            // get the HTML table to export
                            const table = document.getElementById("table_dismissed");

                            // convert the HTML table into a 2D array
                            const data = tableTo2DArray(table);

                            // create a new Workbook object
                            const wb = XLSX.utils.book_new();

                            // add a new worksheet to the Workbook object
                            const ws = XLSX.utils.aoa_to_sheet(data);
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

        });
    </script>
    
    
