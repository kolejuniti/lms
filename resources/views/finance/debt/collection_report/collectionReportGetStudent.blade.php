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
                                No. Matric
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
                                Date payment
                            </th>
                            <th>
                                Amount Payment
                            </th>
                            <th>
                                Total Arrears
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @php
                        $sum = 0;
                        $sum2 = 0;  
                        $total = 0; 
                        @endphp
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
                                    {{ $std->no_matric }}
                                </td>
                                <td>
                                    {{ $data['latest'][$key]->date_of_call }}
                                </td>
                                <td>
                                    {{ $data['latest'][$key]->date_of_payment }}
                                </td>
                                <td>
                                    {{ number_format($data['latest'][$key]->amount, 2) }}
                                </td>
                                <td>
                                    @if($data['payments'][$key] != '')
                                        @foreach($data['payments'][$key] as $pym)
                                            @if($pym->payment_date != null)
                                                {{ $pym->payment_date }}

                                                @php
                                                    $sum2 += $pym->amount;
                                                @endphp
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
                                        {{ number_format($pym->amount, 2) }}
                                        @endforeach
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($data['total_balance'][$key], 2) }}
                                </td>

                                @php
                                    $sum += $data['latest'][$key]->amount;

                                    $total += $data['total_balance'][$key];
                                @endphp
                            </tr>
                        @endforeach 
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                Total
                            </td>
                            <td>
                                {{ number_format($sum, 2) }}
                            </td>
                            <td>

                            </td>
                            <td>
                                {{ number_format($sum2, 2) }}
                            </td>
                            <td>
                                {{ number_format($total, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
   