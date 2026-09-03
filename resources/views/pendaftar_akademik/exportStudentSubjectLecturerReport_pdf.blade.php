<!DOCTYPE html>
<html>
<head>
    <title>Student Subject - Lecturer Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="title">Student Subject - Lecturer Report</div>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">Student Name</th>
                <th width="15%">Student Matrics</th>
                <th width="25%">List of Subject</th>
                <th width="15%">Group Name</th>
                <th width="20%">Lecturer Name</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $matric_counts = [];
                foreach($data as $r) {
                    if(!isset($matric_counts[$r->no_matric])) {
                        $matric_counts[$r->no_matric] = 0;
                    }
                    $matric_counts[$r->no_matric]++;
                }
                $current_matric = ''; 
                $student_counter = 0;
            @endphp
            @forelse($data as $key => $row)
            <tr>
                @if($current_matric != $row->no_matric)
                    @php 
                        $current_matric = $row->no_matric; 
                        $student_counter++;
                        $rowspan = $matric_counts[$row->no_matric];
                    @endphp
                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $student_counter }}</td>
                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $row->student_name }}</td>
                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $row->no_matric }}</td>
                @endif
                <td>{{ $row->course_code }} - {{ $row->course_name }}</td>
                <td>{{ $row->group_name }}</td>
                <td>{{ $row->lecturer_name ?? 'Not Assigned' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No data available in table</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
