@foreach($data['students'] as $key => $student)
<tr>
    <td>
        {{ $key+1 }}
    </td>
    <td>
        {{ $student->name }}
    </td>
    <td>
        {{ $student->ic }}
    </td>
    <td>
        {{ $student->code }}
    </td>
    <td>
        {{ $student->progcode }}
    </td>
    <td>
        {{ $student->no_matric }}
    </td>
    <td>
        {{ $student->SessionName }}
    </td>
    <td>
        {{ $student->semester }}
    </td>
    <td>
        {{ $student->status }}
    </td>
    <td>
        {{ $student->no_tel }}
    </td>
    <td>
        {{ $student->full_address }}
    </td>
    <td>
        {{ $student->dependent_no }}
    </td>
    @php
        $count = count($data['waris'][$key]);
    @endphp
    @for($i = 0; $i < 2; $i++)
    <td>
        {{ $i < $count ? $data['waris'][$key][$i]->name : null }}
    </td>
    <td>
        {{ $i < $count ? $data['waris'][$key][$i]->ic : null }}
    </td>
    <td>
        {{ $i < $count ? $data['waris'][$key][$i]->status : null }}
    </td>
    @endfor
    <td>
        {{ $student->gajikasar }}
    </td>
</tr>
@endforeach