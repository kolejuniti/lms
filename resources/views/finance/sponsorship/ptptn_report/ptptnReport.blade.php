@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "ADM" ? 'layouts.admin' : ''))))

@section('main')
<div class="content-wrapper" style="min-height: 695.8px;">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">PTPTN Report</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                                <li class="breadcrumb-item active" aria-current="page">PTPTN Report</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">PTPTN Report</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-body" style="width: 100%; overflow-x: auto;">
                                    <table id="table_sponsorship" class="table display w-100" style="width: 100%;">
                                        <thead style="background-color: darkcyan;">
                                            <tr>
                                                <th rowspan="2" style="text-align: center; border: 1px solid black;">SEMESTER</th>
                                                <th rowspan="2" style="text-align: center; border: 1px solid black;">PACKAGE</th>
                                                @foreach ($data['session'] as $session)
                                                    <th colspan="2" style="text-align: center; border: 1px solid black;">
                                                        {{ strtoupper($session->SessionName) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach ($data['session'] as $session)
                                                    <th style="text-align: center; border: 1px solid black;">Student</th>
                                                    <th style="text-align: center; border: 1px solid black;">Amount</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['semester'] as $key => $semester)
                                                @foreach ($data['package'] as $key2 => $package)
                                                    <tr>
                                                        @if ($key2 == 0)
                                                            <td style="text-align: center; border: 1px solid black;" rowspan="{{ count($data['package']) }}">
                                                                {{ $semester->id }}
                                                            </td>
                                                        @endif
                                                        <td style="text-align: center; border: 1px solid black;">{{ $package }}</td>
                                                        @foreach ($data['session'] as $key3 => $session)
                                                            <td style="text-align: center; border: 1px solid black;">
                                                                {{ $data['total'][$key][$key2][$key3] ?? 0 }}
                                                            </td>
                                                            <td style="text-align: center; border: 1px solid black;">
                                                                RM{{ number_format($data['amount'][$key][$key2][$key3] ?? 0, 2) }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot style="background-color: darkcyan;">
                                            <tr>
                                                <td colspan="2" style="text-align: center; border: 1px solid black; font-weight: bold;">Total</td>
                                                @foreach ($data['session'] as $key3 => $session)
                                                    @php
                                                        $totalStudents = 0; 
                                                        $totalAmount = 0;
                                                        foreach ($data['semester'] as $key => $semester) {
                                                            foreach ($data['package'] as $key2 => $package) {
                                                                $totalStudents += $data['total'][$key][$key2][$key3] ?? 0;
                                                                $totalAmount += $data['amount'][$key][$key2][$key3] ?? 0;
                                                            }
                                                        }
                                                    @endphp
                                                    <td style="text-align: center; border: 1px solid black; font-weight: bold;">
                                                        {{ $totalStudents }}
                                                    </td>
                                                    <td style="text-align: center; border: 1px solid black; font-weight: bold;">
                                                        RM{{ number_format($totalAmount, 2) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#table_sponsorship').DataTable({
            dom: 'Bfrtip', // Enables buttons
            paging: false, // Disable pagination
            buttons: [
                {
                    extend: 'print',
                    text: 'Print',
                    orientation: 'landscape',
                    customize: function (win) {
                        const today = new Date().toLocaleDateString();
                        const body = $(win.document.body);
                        const tableClone = $('#table_sponsorship').clone();

                        body.html('');
                        body.append(`<h1>PTPTN Report - ${today}</h1>`);
                        body.append(tableClone);

                        body.find('table').css({
                            'border-collapse': 'collapse',
                            'width': '100%',
                        });
                        body.find('th, td').css({
                            'border': '1px solid black',
                            'padding': '8px',
                            'text-align': 'center',
                        });
                    },
                },
                {
                    text: 'Excel',
                    action: function () {
                        const table = document.getElementById('table_sponsorship');
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.table_to_sheet(table);
                        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
                        XLSX.writeFile(wb, 'PTPTN_Report.xlsx');
                    },
                },
            ],
        });
    });
</script>
@endsection
