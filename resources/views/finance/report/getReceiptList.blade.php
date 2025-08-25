<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Student Receipt</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <table id="myTable" class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th style="width: 5%">
                                        Ref No.
                                    </th>
                                    <th style="width: 5%">
                                        Date
                                    </th>
                                    <th style="width: 10%">
                                        Name
                                    </th>
                                    <th style="width: 5%">
                                        IC
                                    </th>
                                    <th style="width: 5%">
                                        Matric No.
                                    </th>
                                    <th style="width: 5%">
                                        Status
                                    </th>
                                    <th style="width: 5%">
                                        Amount
                                    </th>
                                    <th style="width: 5%">
                                        Remark
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['student'] as $key => $req)
                                <tr>
                                    <td>
                                    <a href="/finance/report/receiptlist/getReceiptProof?id={{ $req->id }}&type={{ $req->process_type_id }}" target="_blank">
                                    @if ($req->process_type_id == 7)
                                    PENAJA
                                    @else
                                    {{ $req->ref_no }}
                                    @endif
                                    </a>
                                    </td>
                                    <td>
                                    {{ $req->date }}
                                    </td>
                                    <td>
                                    {{ $req->name }}
                                    </td>
                                    <td>
                                    {{ $req->ic }}
                                    </td>
                                    <td>
                                    {{ $req->no_matric }}
                                    </td>
                                    <td>
                                    {{ $req->status }}
                                    </td>
                                    <td>
                                    {{ number_format($req->amount, 2, '.', '') }}
                                    </td>
                                    <td>
                                    {{ $req->remark }}
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
     </script>
    
