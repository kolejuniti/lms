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
                                Program
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Date Register
                            </th>
                            <th>
                                No. Matric
                            </th>
                            <th>
                                Session
                            </th>
                            <th>
                                Sponsorship Type
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
                        @php
            $record3 = DB::table('tblpaymentdtl')
            ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
            ->where([
                ['tblpayment.student_ic', '000422100803'],
                ['tblpayment.process_status_id', 2],  
                ['tblstudentclaim.groupid', 5],
                ['tblpaymentdtl.amount', '!=', 0]
                ])
            ->select('tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program', 'tblstudentclaim.id as claim_id', DB::raw("'tblpaymentdtl' as source_table"));

            $data['record3'] = DB::table('tblclaimdtl')
            ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
            ->where([
                ['tblclaim.student_ic', '000422100803'],
                ['tblclaim.process_status_id', 2],  
                ['tblstudentclaim.groupid', 5],
                ['tblclaimdtl.amount', '!=', 0]
                ])        
            ->unionALL($record3)
            ->select('tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblstudentclaim.id as claim_id', DB::raw("'tblclaimdtl' as source_table"))
            ->orderBy('date')
            ->get();

            $val2 = 0;

            foreach($data['record3'] as $recKey => $req)
            {

                if(array_intersect([2,3,4,5,11], (array) $req->process_type_id))
                {

                    $total3[$recKey] = $val2 + $req->amount;

                    $val2 = $val2 + $req->amount;

                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id))
                {

                    $total3[$recKey] = $val2 - $req->amount;

                    $val2 = $val2 - $req->amount;

                }

            }

            $data['sum3_3'] = isset($total3) && !empty($total3) ? end($total3) : 0;

                        @endphp
                        @foreach ($data['student'] as $key => $std)
                            <tr>
                                <td>
                                    {{ $data['sum3_3'] }} {{ $data['total_balance'][$key] }}{{ $data['fine_balance'][$key] }} {{ $data['other_balance'][$key] }}
                                </td>
                                <td>
                                    {{ $std->name }}
                                </td>
                                <td>
                                    {{ $std->ic }}
                                </td>
                                <td>
                                    {{ $std->program }}
                                </td>
                                <td>
                                    {{ $std->email }}
                                </td>
                                <td>
                                    {{ $std->date }}
                                </td>
                                <td>
                                    {{ $std->no_matric }}
                                </td>
                                <td>
                                    {{ $std->session }}
                                </td>
                                <td>
                                    {{ $data['sponsorStudent'][$key] ? $data['sponsorStudent'][$key]->name : 'TIADA PENAJA/SENDIRI' }}
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
                                    {{ $data['address'][$key] ? $data['address'][$key]->address : ' ' }}
                                </td>
                                <td>
                                    {{ $data['address'][$key] ? $data['address'][$key]->city : ' ' }}
                                </td>
                                <td>
                                    {{ $data['address'][$key] ? $data['address'][$key]->state_name : ' ' }}
                                </td>
                                <td>
                                    {{ $data['address'][$key] ? $data['address'][$key]->postcode : ' ' }}
                                </td>
                                <td>
                                    {{ $data['address'][$key] ? $data['address'][$key]->country_name : ' ' }}
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
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
   