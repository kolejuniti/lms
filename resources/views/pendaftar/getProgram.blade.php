<div class="row col-md-12">
    <table id="complex_header" class="table table-striped projects display dataTable">
      <thead>
          <tr>
            <th style="width: 1%">
                No.
            </th>
            <th style="width: 15%">
                Name
            </th>
            <th style="width: 15%">
                Program
            </th>
            <th style="width: 10%">
                Intake
            </th>
            <th style="width: 10%">
                Batch
            </th>
            <th style="width: 10%">
                Session
            </th>
            <th style="width: 10%">
                Comment
            </th>
          </tr>
      </thead>
      <tbody id="table">
      @if (isset($programs))
        @foreach ($programs as $key=> $prg)
          <tr>
            <td style="width: 1%">
              {{ $key+1 }}
            </td>
            <td style="width: 15%">
              {{ $prg->name }}
            </td>
            <td style="width: 15%">
              {{ $prg->progname }}
            </td>
            <td>
              {{ $intake[$key]->SessionName }}
            </td>
            <td>
              {{ $batch[$key]->BatchName }}
            </td>
            <td>
              {{ $prg->session }}
            </td>
            <td>
              {!! $prg->comment !!}
            </td>
          </tr>
        @endforeach                    
      @endif
      </tbody>
    </table>
  </div>