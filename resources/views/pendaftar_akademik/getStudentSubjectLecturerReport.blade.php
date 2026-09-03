<div class="table-responsive">
  <table id="report_table" class="table table-bordered">
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
        @php
          $isNewStudent = ($current_matric != $row->no_matric);
        @endphp
      <tr @if($isNewStudent) style="border-top: 2px solid #333;" @endif>
        @if($isNewStudent)
          @php 
            $current_matric = $row->no_matric; 
            $student_counter++;
            $rowspan = $matric_counts[$row->no_matric];
          @endphp
          <td class="align-middle" rowspan="{{ $rowspan }}">{{ $student_counter }}</td>
          <td class="align-middle" rowspan="{{ $rowspan }}">{{ $row->student_name }}</td>
          <td class="align-middle" rowspan="{{ $rowspan }}">{{ $row->no_matric }}</td>
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
</div>
