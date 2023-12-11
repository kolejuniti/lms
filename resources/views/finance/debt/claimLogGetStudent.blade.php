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
                                Last Payment
                            </th>
                            <th>
                                Last Amount
                            </th>
                            <th>
                                Contacted Date
                            </th>
                            <th>
                                Expected Payment Date
                            </th>
                            <th>
                                Current Arrears
                            </th>
                            <th>
                                Penbiayaan Khas Arrears
                            </th>
                            <th>
                                Total Arrears
                            </th>
                            <th>
                                Total Days
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @foreach ($data['student'] as $key => $std)

                            @if(number_format($data['current_balance'][$key], 2) > 0)
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
                                    @foreach($data['payment'][$key] as $pym)
                                    {{ $pym->add_date }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($data['payment'][$key] as $pym)
                                    {{ $pym->amount }}
                                    @endforeach
                                </td>
                                <td>
                                    
                                </td>
                                <td>
                                    
                                </td>
                                <td>
                                    {{ number_format($data['current_balance'][$key], 2) }}
                                </td>
                                <td>
                                    {{ number_format($data['pk_balance'][$key], 2) }}
                                </td>
                                <td>
                                    {{ number_format($data['total_balance'][$key], 2) }}
                                </td>
                                <td>
                                    @foreach($data['payment'][$key] as $pym)
                                    {{ $pym->days }}
                                    @endforeach
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="/finance/debt/claimLog/{{ $std->ic }}">
                                        Payment Log
                                    </a>
                                    <a class="btn btn-warning btn-sm mt-2" href="#">
                                        Letter of Arrears
                                    </a>
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
   