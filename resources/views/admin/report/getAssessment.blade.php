<!-- form start -->
<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Quiz</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['quiz'] as $key => $qz)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $qz->title }}
                                </td>
                                <td>
                                {{ $qz->created_at }}
                                </td>
                                <td>
                                {{ $qz->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Test</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable2" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['test'] as $key => $ts)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $ts->title }}
                                </td>
                                <td>
                                {{ $ts->created_at }}
                                </td>
                                <td>
                                {{ $ts->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Assignment</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable3" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['assign'] as $key => $as)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $as->title }}
                                </td>
                                <td>
                                {{ $as->created_at }}
                                </td>
                                <td>
                                {{ $as->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Other</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable4" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['other'] as $key => $as)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $as->title }}
                                </td>
                                <td>
                                {{ $as->created_at }}
                                </td>
                                <td>
                                {{ $as->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Extra</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable5" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['extra'] as $key => $as)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $as->title }}
                                </td>
                                <td>
                                {{ $as->created_at }}
                                </td>
                                <td>
                                {{ $as->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Midterm</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable6" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['midterm'] as $key => $as)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $as->title }}
                                </td>
                                <td>
                                {{ $as->created_at }}
                                </td>
                                <td>
                                {{ $as->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Final</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <table id="myTable7" class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    No.
                                </th>
                                <th style="width: 5%">
                                    Title
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    Pensyarah
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($data['final'] as $key => $as)
                            <tr>
                                <td>
                                {{ $key+1 }}
                                </td>
                                <td>
                                {{ $as->title }}
                                </td>
                                <td>
                                {{ $as->created_at }}
                                </td>
                                <td>
                                {{ $as->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready( function () {
       $('#myTable').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );

   $(document).ready( function () {
       $('#myTable2').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );

   $(document).ready( function () {
       $('#myTable3').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );

   $(document).ready( function () {
       $('#myTable4').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );

   $(document).ready( function () {
       $('#myTable5').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );

   $(document).ready( function () {
       $('#myTable6').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );

   $(document).ready( function () {
       $('#myTable7').DataTable({
         dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
         
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
       });
   } );
 </script>