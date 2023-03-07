<div class="card mb-3">
    <div class="card-header">
      <b>Status Dismissed</b>
    </div>
    <div class="card-body">
        <div class="card-body">
            <table id="table_dismissed" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 10%">
                        No. IC
                    </th>
                    <th style="width: 15%">
                        Semester
                    </th>
                    <th style="width: 10%">
                        Session
                    </th>
                    <th style="width: 10%">
                        Status
                    </th>
                    <th style="width: 10%">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['dismissed'] as $key=> $ds)
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>
                        {{ $ds->ic }}
                    </td>
                    <td>
                        {{ $ds->semester }}
                    </td>
                    <td>
                        {{ $ds->session }}
                    </td>
                    <td>
                        {{ $ds->status }}
                    </td>
                    <td>
                        {{ $ds->date }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
      <b>Status Active</b>
    </div>
    <div class="card-body">
        <div class="card-body">
            <table id="table_active" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 10%">
                        No. IC
                    </th>
                    <th style="width: 15%">
                        Semester
                    </th>
                    <th style="width: 10%">
                        Session
                    </th>
                    <th style="width: 10%">
                        Status
                    </th>
                    <th style="width: 10%">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['active'] as $key=> $atv)
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>
                        {{ $atv->ic }}
                    </td>
                    <td>
                        {{ $atv->semester }}
                    </td>
                    <td>
                        {{ $atv->session }}
                    </td>
                    <td>
                        {{ $atv->status }}
                    </td>
                    <td>
                        {{ $atv->date }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>