
<label class="form-label"><b>Student Due List</b></label>
<table class="w-100 table table-bordered display margin-top-10 w-p100">
    <thead>
        <tr>
            <th style="width: 1%">
                No.
            </th>
            <th style="width: 10%">
                About
            </th>
            <th style="width: 10%">
                Amount
            </th>
            <th style="width: 5%">
                Unit
            </th>
            <th style="width: 10%">
                Balance
            </th>
        </tr>
    </thead>
    <tbody id="table">
        @foreach ($data['claim'] as $key => $clm)
        @if ($data['balance'][$key] != 0)
        <tr>
            <td>
                {{ $key }}
            </td>
            <td>
                {{ $clm->name }}
            </td>
         
            <td>
                {{ $clm->unit }}
            </td>
        
        </tr>
        @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>
            
            </td>
            <td>
            TOTAL AMOUNT
            </td>
            <td>
            :
            </td>
            </td>
            
            <td>
            <td>
                <div class="col-md-12">
                    <input type="text" class="form-control" name="text_sum" id="text_sum" readonly>
                </div> 
            </td>
        </tr>
    </tfoot>
</table>
