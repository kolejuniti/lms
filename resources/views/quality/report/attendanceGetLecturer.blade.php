<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Attenance List</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <table id="table_dismissed" class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th>
                                        Lecturer
                                    </th>
                                    <th>
                                        Subject
                                    </th>
                                    <th>
                                        Attendance Record
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['lecturer'] as $key => $lct)
                                <tr>
                                    <td>
                                    {{ $lct->name }}
                                    </td>
                                    <td>
                                    @if(count($data['attendance'][$key]) > 0)
                                    <a class="btn btn-success btn-sm mr-2">{{ $lct->course }} ({{ $lct->code }})</a>
                                    @else
                                    <a class="btn btn-danger btn-sm mr-2">{{ $lct->course }} ({{ $lct->code }})</a>
                                    @endif
                                    </td>
                                    <td>
                                    @foreach($data['attendance'][$key] as $att)
                                    {{ $att->classdate }}
                                    @endforeach
                                    </td>
                                </tr>
                                @endforeach
                                <tfoot>
                                </tfoot> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
