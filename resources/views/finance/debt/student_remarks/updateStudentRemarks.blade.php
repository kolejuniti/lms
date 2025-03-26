<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Student Info</b>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Student Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                    </div>
                    <div class="form-group">
                        <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->statusName }}</p>
                    </div>
                    <div class="form-group">
                        <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                    </div>
                </div>
            </div>
            <div class="row" id="stud_info">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="categories">Categories</label>
                        <select 
                            class="form-select" id="categories" name="categories" required>
                            <option value="" selected disabled>-</option>
                            @foreach ($data['category'] as $cat)
                            <option value="{{ $cat->id }}" {{ isset($data['remark']->category_id) && $data['remark']->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="stud_info">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="correction">Correction Amount</label>
                        <input type="float" class="form-control" id="correction" name="correction" value="{{ isset($data['remark']->correction_amount) ? $data['remark']->correction_amount : '' }}" required>
                    </div>
                </div>          
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="current">Current Amount</label>
                        <input type="float" class="form-control" id="current" name="current" value="{{ isset($data['remark']->latest_balance) ? $data['remark']->latest_balance : '' }}" required>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" class="mt-2" rows="10" cols="80" onkeyup="this.value = this.value.toUpperCase();" required>{{ isset($data['remark']->notes) ? $data['remark']->notes : '' }}</textarea>
                    </div>   
                  </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submitForm('{{ $data['student']->ic }}')">Submit</button>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">
        <b>Student Remark List</b>
        </div>
        <div class="card-body">
            <div class="card-body">
                <table id="complex_header" class="table table-striped projects display dataTable">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            No.
                        </th>
                        <th>
                            No. IC
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Correction Amount
                        </th>
                        <th>
                            Latest Balance
                        </th>
                        <th>
                            Remark
                        </th>
                    </tr>
                </thead>
                <tbody id="table">
                @foreach ($data['remarks'] as $key=> $rm)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ $rm->student_ic }}
                        </td>
                        <td>
                            {{ $rm->student_name }}
                        </td>
                        <td>
                            {{ $rm->category }}
                        </td>
                        <td>
                            {{ $rm->correction_amount }}
                        </td>
                        <td>
                            {{ $rm->latest_balance	 }}
                        </td>
                        <td>
                            {!! $rm->notes !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>