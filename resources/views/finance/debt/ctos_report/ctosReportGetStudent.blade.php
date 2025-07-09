<!-- form start -->
<div class="card-body">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="form-group mt-3" style="overflow-x: auto;">
                <label class="form-label">Students</label>
                <table class="w-100 table table-bordered display margin-top-10 w-p100" id="voucher_table">
                    <thead id="voucher_list">
                        <tr>
                            <th></th>
                            <th>CW Operation ID</th>
                            <th>eTR Operaton Code</th>
                            <th>Name</th>
                            <th>Old IC</th>
                            <th>New IC</th>
                            <th>Passport</th>
                            <th>Salutation</th>
                            <th>Sex</th>
                            <th>Marital Status</th>
                            <th>House Status</th>
                            <th>Address</th>
                            <th>Home Name</th>
                            <th>Home State</th>
                            <th>Home Zip Code</th>
                            <th>Home Country</th>
                            <th>Date of Birth</th>
                            <th>Nationality</th>
                            <th>Email</th>
                            <th>Home Tel.</th>
                            <th>Mobile No.</th>
                            <th>Bad No.</th>
                            <th>Account No.</th>
                            <th>Sponsor Constitution</th>
                            <th>Sponsor Old IC</th>
                            <th>Sponsor New IC</th>
                            <th>Sponsor Passport</th>
                            <th>Sponsor Name</th>
                            <th>Sponsor Status</th>
                            <th>Sponsor Remarks</th>
                            <th>Notification Date</th>
                            <th>Relationship Start Date</th>
                            <th>Relationship Type</th>
                            <th>Statement Type</th>
                            <th>Total Outstanding</th>
                            <th>Credit Limit</th>
                            <th>Term Loan</th>
                            <th>Paid Amount</th>
                            <th>Membership Question</th>
                            <th>Debt</th>
                            <th>Deletion Reason Code</th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @foreach ($data['student'] as $key => $std)
                            @if(optional($data['balance'][$key]->first())->balance > 0)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $std->CM }}
                                </td>
                                <td>
                                    {{ $std->etr }}
                                </td>
                                <td>
                                    {{ $std->name }}
                                </td>
                                <td>
                                    {{ $std->old_ic }}
                                </td>
                                <td>
                                    {{ $std->ic }}
                                </td>
                                <td>
                                    {{ $std->passport }}
                                </td>
                                <td>
                                    {{ $std->salution }}
                                </td>
                                <td>
                                    {{ ($std->sex_id == 1) ? 'M' : 'F' }}
                                </td>
                                <td>
                                    {{ $std->marital_status }}
                                </td>
                                <td>
                                    {{ $std->house_status }}
                                </td>
                                <td>
                                    {{ $std->address }}
                                </td>
                                <td>
                                    {{ $std->city }}
                                </td>
                                <td>
                                    {{ $std->state_name }}
                                </td>
                                <td>
                                    {{ $std->postcode }}
                                </td>
                                <td>
                                    {{ $std->country_name }}
                                </td>
                                <td>
                                    {{ $std->date_birth }}
                                </td>
                                <td>
                                    {{ $std->nationality_name }}
                                </td>
                                <td>
                                    {{ $std->email }}
                                </td>
                                <td>
                                    {{ $std->no_tel }}
                                </td>
                                <td>
                                    {{ $std->no_tel }}
                                </td>
                                <td>
                                    {{ $std->ref_no }}
                                </td>
                                <td>
                                    {{ $std->account_no }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->sponsor ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->old_ic ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->ic ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->passport ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->name ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->sponsor_status ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->remarks ?? null }}
                                </td>
                                <td>
                                    {{-- {{ $data['waris'][$key]->notification ?? null }} --}}
                                    {{ $std->date_add }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->relationship ?? null }}
                                </td>
                                <td>
                                    {{ $data['waris'][$key]->type ?? null }}
                                </td>
                                @if(count($data['balance'][$key]) > 0)
                                @foreach($data['balance'][$key] as $blc)
                                <td>
                                    {{ $blc->date }}
                                </td>
                                <td>
                                    {{ $blc->balance }}
                                </td>
                                <td>
                                    {{ $blc->cr_limit }}
                                </td>
                                <td>
                                    {{ $blc->cr_term }}
                                </td>
                                @endforeach
                                @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @endif
                                @if(count($data['lastPayment'][$key]) > 0)
                                @foreach($data['lastPayment'][$key] as $lsp)
                                <td>
                                    {{ $lsp->last_payment }}
                                </td>
                                <td>
                                    {{ $lsp->option }}
                                </td>
                                <td>
                                    {{ $lsp->debt_type }}
                                </td>
                                <td>
                                    {{ $lsp->deletion }}
                                </td>
                                @endforeach
                                @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @endif
                            </tr>
                            @endif
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>