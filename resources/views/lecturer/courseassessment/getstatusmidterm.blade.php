  <div class="col-sm-12">
    <table id="myTable" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th style="width: 15%">
            Name
          </th>
          <th style="width: 5%">
            Matric No.
          </th>
          <th style="width: 20%">
            Submission Date
          </th>
          <th style="width: 10%">
            Status
          </th>
          <th style="width: 5%">
            Marks
          </th>
          <th style="width: 20%">
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($misterm as $key => $qz)

        @if (count($status[$key]) > 0)
          @php
            $alert = "badge bg-success";
          @endphp
        @else
          @php
            $alert = "badge bg-danger";
          @endphp
        @endif
        
        <tr>
          <td style="width: 1%">
              {{ $key+1 }}
          </td>
          <td style="width: 15%">
            <span class="{{ $alert }}">{{ $qz->name }}</span>
          </td>
          <td style="width: 5%">
            <span class="">{{ $qz->no_matric }}</span>
          </td>
          @if (count($status[$key]) > 0)
            @foreach ($status[$key] as $keys => $sts)
            <td style="width: 20%">
                  {{ empty($sts) ? '-' : $sts->submittime }}
            </td>
            <td>
                  {{ empty($sts) ? '-' : $sts->status }}
            </td>
            <td>
                  {{ empty($sts) ? '-' : $sts->final_mark }} / {{ $qz->total_mark }}
            </td>
            <td class="project-actions text-center" >
              <a class="btn btn-success btn-sm mr-2" href="/lecturer/misterm/{{ request()->misterm }}/{{ $sts->userid }}/result">
                  <i class="ti-user">
                  </i>
                  Students
              </a>
            </td>                                               
            @endforeach
          @else
            <td style="width: 20%">
              -
            </td>
            <td>
              -
            </td>
            <td>
            -
            </td> 
            <td>

            </td>
          @endif
        </tr> 
        @endforeach
      </tbody>
    </table>
  </div>
                      