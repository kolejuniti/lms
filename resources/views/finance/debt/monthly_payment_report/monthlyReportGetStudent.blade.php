<!-- form start -->
<div class="card-body">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="form-group mt-3" style="overflow-x: auto;">
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
                                Date Register
                            </th>
                            <th>
                                No. Matric
                            </th>
                            <th>
                                Sponsorship
                            </th>
                            <th>
                                Package
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                                Address
                            </th>
                            <th>
                                Home Name
                            </th>
                            <th>
                                Home State
                            </th>
                            <th>
                                Home Zip Code
                            </th>
                            <th>
                                Home Country
                            </th>
                            <th>
                                Date End
                            </th>
                            <th>
                                Date Contacted
                            </th>
                            <th>
                                Note
                            </th>
                            @foreach($data['dateRange'] as $dr)
                            <th>
                                {{ $dr }}
                            </th>
                            @endforeach
                            <th>
                                Total (RM)
                            </th>
                            <th>
                                Balance (RM)
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @foreach ($data['student'] as $key => $std)
                            @if(number_format($data['total_balance'][$key], 2) > 0)
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
                                    {{ $std->date }}
                                </td>
                                <td>
                                    {{ $std->no_matric }}
                                </td>
                                <td>
                                    {{ $data['sponsor'][$key] ? $data['sponsor'][$key]->package_name : ' ' }}
                                </td>
                                <td>
                                    {{ $data['sponsor'][$key] ? $data['sponsor'][$key]->payment_type_name : ' ' }}
                                </td>
                                <td>
                                    {{ $data['type'][$key] ? $data['type'][$key] : ' ' }}
                                </td>
                                <td>
                                    {{ $data['sponsor'][$key] ? $data['sponsor'][$key]->amount : ' ' }}
                                </td>
                                <td>
                                    {{ $data['address'][$key]->address }}
                                </td>
                                <td>
                                    {{ $data['address'][$key]->city }}
                                </td>
                                <td>
                                    {{ $data['address'][$key]->state_name }}
                                </td>
                                <td>
                                    {{ $data['address'][$key]->postcode }}
                                </td>
                                <td>
                                    {{ $data['address'][$key]->country_name }}
                                </td>
                                <td>
                                    {{ $std->graduate }}
                                </td>
                                <td>
                                    {{ $data['log'][$key] ? $data['log'][$key]->date_of_call : '0' }}
                                </td>
                                <td>
                                    {{ $data['log'][$key] ? $data['log'][$key]->note : '0' }}
                                </td>
                                @php
                                $totalALL = 0;
                                @endphp
                                @foreach($data['dateRange'] as $key2 => $dr)
                                    <td @if($data['amount'][$key][$key2] != 0.00) style="background-color: orange;" @endif>
                                        {{ $data['amount'][$key][$key2] }}
                                    </td>
                                    @php
                                        $totalALL += $data['amount'][$key][$key2];
                                    @endphp
                                @endforeach
                                <td>
                                    {{ number_format($totalALL, 2) }}
                                </td>
                                <td>
                                    {{ number_format($data['total_balance'][$key], 2) }}
                                </td>
                            </tr>
                            @endif
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
   