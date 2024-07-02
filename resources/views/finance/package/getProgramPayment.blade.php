
<div class="form-group">
    <label class="form-label"><b>Program List</b></label>
    <table class="w-100 table table-bordered display margin-top-10 w-p100">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 10%">
                    Code
                </th>
                <th style="width: 10%">
                    Name
                </th>
                <th style="width: 5%">
                </th>
            </tr>
        </thead>
        <tbody id="table">
            @foreach ($data['unregistered'] as $key => $rgs)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>
                    {{ $rgs->progcode }}
                </td>
                <td>
                    {{ $rgs->progname }}
                </td>
                <td>
                    <a class="btn btn-success btn-sm pr-2" href="#" onclick="Register('{{ $rgs->id }}','{{ $data['id']}}')">
                        <i class="ti-pencil-alt">
                        </i>
                        REGISTER
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <hr>
    <br>

    <label class="form-label" style="text-align: center"><b>Registered</b></label>
    <table class="w-100 table table-bordered display margin-top-10 w-p100">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 10%">
                    Code
                </th>
                <th style="width: 10%">
                    Name
                </th>
                <th style="width: 10%">
                    Session
                </th>
                <th style="width: 5%">
                </th>
            </tr>
        </thead>
        <tbody id="table">
            @foreach ($data['registered'] as $key => $rgs)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>
                    {{ $rgs->progcode }}
                </td>
                <td>
                    {{ $rgs->progname }}
                </td>
                <td>
                    {{ $rgs->SessionName }}
                </td>
                <td>
                    <a class="btn btn-danger btn-sm pr-2" href="#" onclick="deleteReg('{{ $rgs->id }}','{{ $data['id']}}')">
                        <i class="ti-pencil-alt">
                        </i>
                        DELETE
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

