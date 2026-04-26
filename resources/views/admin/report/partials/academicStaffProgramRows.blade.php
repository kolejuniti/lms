@forelse($rows as $row)
  @php
    $coursesThis = is_array($row['courses_this_program'] ?? null)
      ? $row['courses_this_program']
      : (empty($row['courses_this_program']) ? [] : explode("\n", (string) $row['courses_this_program']));

    $coursesOther = is_array($row['courses_other_program'] ?? null)
      ? $row['courses_other_program']
      : (empty($row['courses_other_program']) ? [] : explode("\n", (string) $row['courses_other_program']));

    $thisCount = count($coursesThis);
    $otherCount = count($coursesOther);
    $rowspan = max($thisCount, $otherCount, 1);
  @endphp

  @for($i = 0; $i < $rowspan; $i++)
    <tr>
      @if($i === 0)
        <td rowspan="{{ $rowspan }}" class="align-top">{{ $row['no'] }}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{{ $row['name_designation'] }}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{{ $row['appointment_status'] }}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{{ $row['nationality'] }}</td>
      @endif

      <td class="align-top">{{ $coursesThis[$i] ?? '-' }}</td>
      <td class="align-top">{{ $coursesOther[$i] ?? '-' }}</td>

      @if($i === 0)
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['qualification'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['field'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['year'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['institution'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['research_focus'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['positions'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['employer'])) !!}</td>
        <td rowspan="{{ $rowspan }}" class="align-top">{!! nl2br(e($row['years_service'])) !!}</td>
      @endif
    </tr>
  @endfor
@empty
  <tr>
    <td colspan="14" class="text-center text-muted">No data found.</td>
  </tr>
@endforelse

