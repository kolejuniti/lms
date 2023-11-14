
<table>
  <thead>
    <tr>
      <th>
      Faculty
      </th>
      <th>
      Lecturer
      </th>
      <th>
      Subject
      </th>
      <th>
      Content
      </th>
      <th>
      Quiz
      </th>
      <th>
      Test
      </th>
      <th>
      Assignment
      </th>
      <th>
      Usage
      </th>
      <th>
      Active
      </th>
    </tr>
  </thead>
  <tbody>
    @foreach($data['faculty'] as $facultyKey => $facultyValue)
        <?php $facultyRowCount = 0; ?>
        @foreach($data['lecturer'][$facultyKey] as $nameKey => $nameValue)
            <?php $facultyRowCount += count($data['course'][$facultyKey][$nameKey]); ?>
        @endforeach

        <?php $isFacultyDisplayed = false; ?>
        @foreach($data['lecturer'][$facultyKey] as $nameKey => $nameValue)
            <?php $isNameDisplayed = false; ?>
            @foreach($data['course'][$facultyKey][$nameKey] as $courseKey => $courseValue)
                <tr>
                    @if(!$isFacultyDisplayed)
                        <td rowspan="{{ $facultyRowCount }}">{{ $facultyValue->facultyname }}</td>
                        <?php $isFacultyDisplayed = true; ?>
                    @endif
                    @if(!$isNameDisplayed)
                        <td rowspan="{{ count($data['course'][$facultyKey][$nameKey]) }}">{{ $nameValue->name }}</td>
                        <?php $isNameDisplayed = true; ?>
                    @endif
                    <td>{{ $courseValue->course_name }}</td>
                    <td>
                      @foreach($data['content'][$facultyKey][$nameKey][$courseKey] as $content)
                      Chapter {{ $content->ChapterNo }} : {{ $content->DrName }} <br>
                      @endforeach
                    </td>
                    <td>{{ $data['quiz'][$facultyKey][$nameKey][$courseKey] }}</td>
                    <td>{{ $data['test'][$facultyKey][$nameKey][$courseKey] }}</td>
                    <td>{{ $data['assignment'][$facultyKey][$nameKey][$courseKey] }}</td>
                    <td>{{ $data['usage'][$facultyKey][$nameKey][$courseKey] }}</td>
                    <td>{{ $data['assessment'][$facultyKey][$nameKey][$courseKey] }}</td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
  </tbody>
</table>
