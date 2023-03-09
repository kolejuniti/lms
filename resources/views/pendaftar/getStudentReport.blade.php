
    <thead>
      <tr>
        <th colspan="2"></th>
        <th colspan="2"></th>
        <th style="text-align: center" colspan="2">Holding</th>
        <th style="text-align: center" colspan="2">40</th>
        <th style="text-align: center" colspan="2">40</th>
        <th style="text-align: center" colspan="2">10</th>
        <th style="text-align: center" colspan="2">6</th>
        <th style="text-align: center" colspan="2">2</th>
        <th style="text-align: center" colspan="2">0</th>
        <th colspan="2"></th>
        <th colspan="2"></th>
        <th colspan="2"></th>
      </tr>
      <tr>
        <th style="text-align: center; width: 1%" rowspan="2">
        Faculty
        </th>
        <th style="text-align: center; width: 20%" rowspan="2">
        Program
        </th>
        <th style="text-align: center; width: 5%" colspan="4">
        Sem 1
        </th>
        <th style="text-align: center; width: 5%" colspan="2">
        Sem 2
        </th>
        <th style="text-align: center; width: 5%" colspan="2">
        Sem 3
        </th>
        <th style="text-align: center; width: 5%" colspan="2">
        Sem 4
        </th>
        <th style="text-align: center; width: 5%" colspan="2">
        Sem 5
        </th>
        <th style="text-align: center; width: 5%" colspan="2">
        Sem 6
        </th>
        <th style="width: 10%; text-align: center" rowspan="2">
        Active
        </th>
        <th style="width: 10%; text-align: center" rowspan="2">
        Postpone
        </th>
        <th style="width: 10%; text-align: center" rowspan="2">
        Dismissed
        </th>
      </tr>
      <tr>
        <th>L</th>
        <th>P</th>
        <th>L</th>
        <th>P</th>
        <th>L</th>
        <th>P</th>
        <th>L</th>
        <th>P</th>
        <th>L</th>
        <th>P</th>
        <th>L</th>
        <th>P</th>
        <th>L</th>
        <th>P</th>
    </tr>
    </thead>
    <tbody id="table">
      @foreach ($data['program'] as $key=>$prg)
      <tr>
        <td style="text-align: center">
        {{ $prg->facultyname }} <br>
        {{ $data['sum'][$key] }}
        </td>
        <td>
        {{ $prg->progname }}
        </td>
        <td>
        @foreach ((array) $data['ms1'][$key] as $ms1)
        {{ $ms1 }}
        @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms1'][$key] as $ms1)
          {{ $ms1 }}
          @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms1'][$key] as $ms1)
          {{ $ms1 }}
          @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms1'][$key] as $ms1)
          {{ $ms1 }}
          @endforeach
        </td>
        <td>
        @foreach ((array) $data['ms2'][$key] as $ms2)
        {{ $ms2 }}
        @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms2'][$key] as $ms2)
          {{ $ms2 }}
          @endforeach
        </td>
        <td>
        @foreach ((array) $data['ms3'][$key] as $ms3)
        {{ $ms3 }}
        @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms3'][$key] as $ms3)
          {{ $ms3 }}
          @endforeach
        </td>
        <td>
        @foreach ((array) $data['ms4'][$key] as $ms4)
        {{ $ms4 }}
        @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms4'][$key] as $ms4)
          {{ $ms4 }}
          @endforeach
        </td>
        <td>
        @foreach ((array) $data['ms5'][$key] as $ms5)
        {{ $ms5 }}
        @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms5'][$key] as $ms5)
          {{ $ms5 }}
          @endforeach
        </td>
        <td>
        @foreach ((array) $data['ms6'][$key] as $ms6)
        {{ $ms6 }}
        @endforeach
        </td>
        <td>
          @foreach ((array) $data['ms6'][$key] as $ms6)
          {{ $ms6 }}
          @endforeach
        </td>
        <td style="text-align: center">
        @foreach ((array) $data['active'][$key] as $active)
        {{ $active }}
        @endforeach
        </td>
        <td style="text-align: center">
        @foreach ((array) $data['postpone'][$key] as $postpone)
        {{ $postpone }}
        @endforeach
        </td>
        <td style="text-align: center">
        @foreach ((array) $data['dismissed'][$key] as $dismissed)
        {{ $dismissed }}
        @endforeach
        </td>
      </tr>
      @endforeach
      
    </tbody>
    <tfoot>
      <tr>
        <td>
          
        </td>
        <td >
          TOTAL STUDENT
        </td>
        @php
          $semester = DB::table('semester')->get();
        @endphp
        @foreach ($semester as $sem)
        @php
          $total = count(DB::table('students')->where('semester', $sem->id)->get())
        @endphp
        <td colspan="2" style="text-align: center">
          {{ $total }}
        </td>
        @endforeach
        <td style="text-align: center">
          @php
            $active = count(DB::table('students')->where([
                  ['students.status', 2],
                  //['students.campus_id', 1]
                  ])->get());
          @endphp
          {{ $active }}
        </td>
        <td style="text-align: center">
          @php
            $postpone = count(DB::table('students')->where([
                  ['students.status', 2],
                  ['students.campus_id', 2]
                  //['students.campus_id', 1]
                  ])->get());
          @endphp
          {{ $postpone }}
        </td>
        <td style="text-align: center">
          @php
            $dismissed = count(DB::table('students')->where([
                  ['students.status', 3]
                  //['students.campus_id', 1]
                  ])->get());
          @endphp
          {{ $dismissed }}
        </td>
      </tr>
    </tfoot>
 