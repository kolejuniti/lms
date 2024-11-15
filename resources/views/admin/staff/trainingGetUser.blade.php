<!-- form start -->
<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>User Info</b>
    </div>
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="form-group">
                    <p>Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['user']->name }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['user']->ic }}</p>
                </div>
            </div>
        </div>
        <div class="row" id="stud_info">
            <div class="col-md-12">
                <div class="form-group">
                  <label class="form-label" for="name">Course Name</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                  <label class="form-label" for="organizer">organizer</label>
                  <input type="text" class="form-control" id="organizer" name="organizer" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="type">Training Type</label>
                    <select class="form-select" id="type" name="type">
                    <option value="-" selected disabled>-</option>
                    <option value="In The Field">In The Field</option>
                    <option value="Outside The Field">Outside The Field</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="start_date">Start Date</label>
                  <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="end_date">End Date</label>
                  <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="start_time">Start Time</label>
                  <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="end_time">End Time</label>
                  <input type="time" class="form-control" id="end_time" name="end_time" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="year">Year</label>
                  <input type="text" class="form-control" id="year" name="year" required>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submitForm('{{ $data['user']->ic }}')">Submit</button>
    </div>
</div>
<div class="card mb-3">
<div class="card-header">
  <b>List Of Course And Training</b>
</div>
<div class="card-body">
    <div class="card-body">
        <table id="complex_header" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 15%">
                    Course Name
                </th>
                <th style="width: 15%">
                    Organizer
                </th>
                <th style="width: 10%">
                    Training Type
                </th>
                <th style="width: 10%">
                    Start Date
                </th>
                <th style="width: 10%">
                    End Date
                </th>
                <th style="width: 10%">
                    Start Time
                </th>
                <th style="width: 20%">
                    End Time
                </th>
                <th style="width: 10%">
                    Year
                </th>
                <th>

                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['training'] as $key => $tr)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>
                    {{ $tr->training_name }}
                </td>
                <td>
                    {{ $tr->organizer }}
                </td>
                <td>
                    {{ $tr->training_type }}
                </td>
                <td>
                    {{ $tr->start_date }}
                </td>
                <td>
                    {{ $tr->end_date }}
                </td>
                <td>
                    {{ $tr->start_time }}
                </td>
                <td>
                    {!! $tr->end_time !!}
                </td>
                <td>
                    {{ $tr->year }}
                </td>
                <th>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('{{ $tr->id }}','{{ $tr->user_ic }}')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
        </table>
    </div>
</div>
</div>

<script>
    var user = '{{ $data["user"]->name }}';
</script>