<table>
  <thead>
    <tr>
      <th rowspan="2">#</th>
      <th rowspan="2">Name and Designation of Academic Staff</th>
      <th rowspan="2">Appointment Status (full-time, part-time, contract, etc.)</th>
      <th rowspan="2">Nationality</th>
      <th rowspan="2">Courses Taught in This Programme</th>
      <th rowspan="2">Courses Taught in Other Programmes</th>
      <th colspan="4">Academic Qualifications</th>
      <th rowspan="2">Research Focus Areas (Bachelor and above)</th>
      <th colspan="3">Past Work Experience</th>
    </tr>
    <tr>
      <th>Qualifications</th>
      <th>Field of Specialisation</th>
      <th>Year of Award</th>
      <th>Name of Awarding Institution and Country</th>
      <th>Positions Held</th>
      <th>Employer</th>
      <th>Years of Service (start and end)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($rows as $row)
      <tr>
        <td>{{ $row['no'] }}</td>
        <td>{{ $row['name_designation'] }}</td>
        <td>{{ $row['appointment_status'] }}</td>
        <td>{{ $row['nationality'] }}</td>
        @php
          $coursesThisText = $row['courses_this_program_export']
            ?? (is_array($row['courses_this_program'] ?? null) ? implode("\n", $row['courses_this_program']) : (string) ($row['courses_this_program'] ?? ''));
          $coursesOtherText = $row['courses_other_program_export']
            ?? (is_array($row['courses_other_program'] ?? null) ? implode("\n", $row['courses_other_program']) : (string) ($row['courses_other_program'] ?? ''));
        @endphp
        <td>{!! nl2br(e($coursesThisText)) !!}</td>
        <td>{!! nl2br(e($coursesOtherText)) !!}</td>
        <td>{{ $row['qualification'] }}</td>
        <td>{{ $row['field'] }}</td>
        <td>{{ $row['year'] }}</td>
        <td>{{ $row['institution'] }}</td>
        <td>{{ $row['research_focus'] }}</td>
        <td>{{ $row['positions'] }}</td>
        <td>{{ $row['employer'] }}</td>
        <td>{{ $row['years_service'] }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

