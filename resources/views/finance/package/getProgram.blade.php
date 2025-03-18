<div class="form-group">
    <form id="semesterForm">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label" for="start_at"><b>Start At</b></label>
                <select class="form-select" id="start_at" name="start_at">
                    <option value="" {{ (!isset($data['tabungkhas']->start_at) || $data['tabungkhas']->start_at == '') ? 'selected' : '' }}>-</option>
                    <option value="1" {{ (isset($data['tabungkhas']->start_at) && $data['tabungkhas']->start_at == '1') ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ (isset($data['tabungkhas']->start_at) && $data['tabungkhas']->start_at == '2') ? 'selected' : '' }}>Semester 2</option>
                    <option value="3" {{ (isset($data['tabungkhas']->start_at) && $data['tabungkhas']->start_at == '3') ? 'selected' : '' }}>Semester 3</option>
                    <option value="4" {{ (isset($data['tabungkhas']->start_at) && $data['tabungkhas']->start_at == '4') ? 'selected' : '' }}>Semester 4</option>
                    <option value="5" {{ (isset($data['tabungkhas']->start_at) && $data['tabungkhas']->start_at == '5') ? 'selected' : '' }}>Semester 5</option>
                    <option value="6" {{ (isset($data['tabungkhas']->start_at) && $data['tabungkhas']->start_at == '6') ? 'selected' : '' }}>Semester 6</option>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="button" class="btn btn-primary" onclick="updateStartAt('{{ $data['id'] }}')">
                    <i class="ti-save"></i>
                    Update
                </button>
            </div>
        </div>
    </form>

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
                    <a class="btn btn-danger btn-sm pr-2" href="#" onclick="unRegister('{{ $rgs->id }}','{{ $data['id']}}')">
                        <i class="ti-pencil-alt">
                        </i>
                        UN-REGISTER
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <br>
    <hr>
    <br>
    
    <label class="form-label"><b>Un-Registered</b></label>
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
</div>

