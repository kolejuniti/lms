<!-- form start -->
<div class="card mb-3">
    <div class="card-header">
      <b>Subject List</b>
    </div>
    <div class="card-body">
        <form id="subjectForm">
            <div class="card-body">
                <table id="complex_header" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 5%">
                                Name
                            </th>
                            <th style="width: 10%">
                                Code
                            </th>
                            <th style="width: 5%">
                                Meeting Hour
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @foreach ($data['subject'] as $key=> $crs)
                        <tr>
                            <td>
                                {{ $key+1 }}
                            </td>
                            <td>
                                {{ $crs->course_name }}
                            </td>
                            <td>
                                {{ $crs->course_code }}
                            </td>
                            <td>
                                <input type="text" name="m_id[]" value="{{ $crs->courseID }}" hidden>
                                <input type="text" name="m_hour[]" id="m_hour" value="{{ $crs->meeting_hour }}" class="form-control">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            <div>
                <button type="button" class="btn btn-primary pull-right mb-3" onclick="submitForm()">Submit</button>
            </div>
        </form>
    </div>
</div>