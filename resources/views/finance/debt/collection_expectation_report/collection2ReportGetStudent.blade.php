<!-- form start -->
<div class="card-body">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="form-group mt-3">
                <label class="form-label">Students</label>
                <table class="w-100 table table-bordered display margin-top-10 w-p100" id="voucher_table">
                    <thead id="voucher_list">
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                IC / Passport No.
                            </th>
                            <th>
                                Last Date payment
                            </th>
                            <th>
                                Last Amount Payment
                            </th>
                            <th>
                                Date Contacted
                            </th>
                            <th>
                                Date Expexted Payment
                            </th>
                            <th>
                                Amount Expected Payment
                            </th>
                            <th>
                                Total Arrears
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @foreach ($data['student'] as $key => $std)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $std->name }}
                                </td>
                                <td>
                                    {{ $std->ic }}
                                </td>
                                <td>
                                    @if($data['payments'][$key] != '')
                                        @foreach($data['payments'][$key] as $pym)
                                            @if($pym->payment_date != null)
                                                {{ $pym->payment_date }}
                                            @else
                                                -
                                            @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($data['payments'][$key] != '')
                                        @foreach($data['payments'][$key] as $pym)
                                        {{ $pym->amount }}
                                        @endforeach
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td>
                                    {{ $data['latest'][$key]->date_of_call }}
                                </td>
                                <td>
                                    {{ $data['latest'][$key]->date_of_payment }}
                                </td>
                                <td>
                                    {{ $data['latest'][$key]->amount }}
                                </td>
                                <td>
                                    {{ number_format($data['total_balance'][$key], 2) }}
                                </td>
                            </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
   