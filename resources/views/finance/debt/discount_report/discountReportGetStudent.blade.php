<!-- form start -->
<div class="card-body">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="form-group mt-3" style="overflow-x: auto;">
                <label class="form-label">Discount Report</label>
                <table class="w-100 table table-bordered display margin-top-10 w-p100" id="discount_table">
                    <thead id="discount_list">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">No Matrik</th>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 15%;">No K/P</th>
                            <th style="width: 15%;">Tarikh</th>
                            <th style="width: 10%;">Jumlah Tunggakan (RM)</th>
                            <th style="width: 10%;">Terimaan Diskaun (RM)</th>
                            <th style="width: 10%;">Bayaran Pelajar (RM)</th>
                        </tr>
                    </thead>
                    <tbody id="table">
                        @php 
                            $totalTunggakan = 0;
                            $totalDiskaun = 0; 
                            $totalBayaran = 0;
                        @endphp
                        @foreach ($data['student'] as $key => $std)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $std->ref_no }}</td>
                                <td>{{ $std->name }}</td>
                                <td>{{ $std->ic }}</td>
                                <td>{{ date('d/m/Y', strtotime($std->date)) }}</td>
                                <td style="text-align: right;">
                                    @if(isset($data['sum'][$key]) && $data['sum'][$key] > 0)
                                        {{ number_format($data['sum'][$key], 2) }}
                                        @php $totalTunggakan += $data['sum'][$key]; @endphp
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if($std->amount > 0)
                                        {{ number_format($std->amount, 2) }}
                                        @php $totalDiskaun += $std->amount; @endphp
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if(isset($data['actual'][$key]) && $data['actual'][$key] > 0)
                                        {{ number_format($data['actual'][$key], 2) }}
                                        @php $totalBayaran += $data['actual'][$key]; @endphp
                                    @else
                                        0.00
                                    @endif
                                </td>
                            </tr>
                        @endforeach 
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td colspan="5" style="text-align: right; padding-right: 20px;">JUMLAH:</td>
                            <td style="text-align: right;">{{ number_format($totalTunggakan, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($totalDiskaun, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($totalBayaran, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div> 