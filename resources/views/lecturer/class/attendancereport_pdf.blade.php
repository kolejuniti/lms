<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        @page { margin: 14mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #111; }
        h1 { font-size: 14px; margin: 0 0 6px 0; }
        .meta { margin-bottom: 10px; }
        .meta table { width: 100%; border-collapse: collapse; }
        .meta td { padding: 1px 0; vertical-align: top; }
        .group-title { font-size: 12px; margin: 10px 0 6px 0; font-weight: 700; }
        table.report { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.report th, table.report td { border: 1px solid #222; padding: 3px; word-wrap: break-word; }
        table.report th { background: #f2f2f2; text-align: center; font-weight: 700; }
        .center { text-align: center; }
        .page-break { page-break-after: always; }
        .header { position: fixed; top: 0; left: 0; right: 0; font-size: 9px; color: #444; width: 100%; }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { padding: 0; }
        .header .left { text-align: left; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 9px; color: #444; width: 100%; }
        .footer table { width: 100%; border-collapse: collapse; }
        .footer td { padding: 0; }
        .footer .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>UCMS - BPK.UNITI.BKD.03/01</td>
            </tr>
        </table>
    </div>
    <h1>Attendance Report</h1>

    <div class="meta">
        <table>
            <tr>
                <td style="width: 90px;"><strong>Lecturer</strong></td>
                <td style="width: 8px;">:</td>
                <td>{{ $user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Course</strong></td>
                <td>:</td>
                <td colspan="4">
                    {{ trim(($course->course_code ?? '') . ' - ' . ($course->course_name ?? ''), ' -') ?: '-' }}
                </td>
            </tr>
            <tr>
                <td><strong>Session</strong></td>
                <td>:</td>
                <td colspan="4">{{ $session->SessionName ?? ($session->SessionID ?? '-') }}</td>
            </tr>
        </table>
    </div>

    @if(!empty($groups) && count($groups) > 0)
        @foreach($groups as $ky => $grp)
            <div class="group-title">Group {{ $grp->group_name }}</div>

            <table class="report">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Student Name</th>
                        <th width="10%">Matric No.</th>
                        @if(!empty($list[$ky]))
                            @foreach($list[$ky] as $k => $ls)
                                <th style="width: 70px;">
                                    {{ \Carbon\Carbon::parse($ls->classdate)->format('d/m/Y') }}
                                </th>
                            @endforeach
                        @endif
                        <th style="width: 70px;">Total Present</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($students[$ky]) && count($students[$ky]) > 0)
                        @foreach($students[$ky] as $idx => $std)
                            @php
                                $totalClasses = isset($list[$ky]) ? count($list[$ky]) : 0;
                                $presentCount = 0;
                                if (!empty($list[$ky])) {
                                    foreach ($list[$ky] as $k => $ls) {
                                        if (($status[$ky][$idx][$k] ?? null) === 'Present') {
                                            $presentCount++;
                                        }
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="center">{{ $idx + 1 }}</td>
                                <td>{{ $std->name ?? '-' }}</td>
                                <td class="center">{{ $std->no_matric ?? '-' }}</td>
                                @if(!empty($list[$ky]))
                                    @foreach($list[$ky] as $k => $ls)
                                        <td class="center">
                                            @php($st = $status[$ky][$idx][$k] ?? null)
                                            @if($st === 'Present')
                                                ✓
                                            @elseif($st === 'Absent')
                                                ✗
                                            @else
                                                {{ $st ?? '-' }}
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                                <td class="center">{{ $presentCount }}/{{ $totalClasses }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ 4 + (isset($list[$ky]) ? count($list[$ky]) : 0) }}" class="center">
                                No students found for this group.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if($ky < count($groups) - 1)
                <div class="page-break"></div>
            @endif
        @endforeach
    @else
        <p>No group available.</p>
    @endif

    <div class="footer">
        <table>
            <tr>
                <td>UCMS - BPK.UNITI.BKD.03/01</td>
                <td class="right">Generated: {{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
