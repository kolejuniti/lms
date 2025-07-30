<!-- Discount Records Table -->
<div class="card-body">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="form-group mt-3" style="overflow-x: auto;">
                <label class="form-label">Discount Records 
                    @if(isset($data['filter_info']))
                        - {{ $data['filter_info'] }}
                    @endif
                </label>
                
                @if(count($data['records']) > 0)
                <table class="w-100 table table-bordered display margin-top-10 w-p100" id="discount_records_table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">No Matrik</th>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 15%;">No K/P</th>
                            <th style="width: 10%;">Diskaun (%)</th>
                            <th style="width: 10%;">Jumlah Tunggakan (RM)</th>
                            <th style="width: 10%;">Terimaan Diskaun (RM)</th>
                            <th style="width: 10%;">Bayaran Pelajar (RM)</th>
                            <th style="width: 15%;">Tarikh</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $totalTunggakan = 0;
                            $totalDiskaun = 0; 
                            $totalBayaran = 0;
                        @endphp
                        @foreach ($data['records'] as $key => $record)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $record->no_matric ?? 'N/A' }}</td>
                                <td>{{ $record->name ?? 'N/A' }}</td>
                                <td>{{ $record->student_ic }}</td>
                                <td style="text-align: right;">{{ number_format($record->discount, 2) }}%</td>
                                <td style="text-align: right;">
                                    {{ number_format($record->total_arrears, 2) }}
                                    @php $totalTunggakan += $record->total_arrears; @endphp
                                </td>
                                <td style="text-align: right;">
                                    {{ number_format($record->received_discount, 2) }}
                                    @php $totalDiskaun += $record->received_discount; @endphp
                                </td>
                                <td style="text-align: right;">
                                    {{ number_format($record->payment, 2) }}
                                    @php $totalBayaran += $record->payment; @endphp
                                </td>
                                <td>{{ date('d/m/Y', strtotime($record->created_at)) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editRecord({{ $record->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRecord({{ $record->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
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
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="alert alert-info">
                    <h5><i class="icon fa fa-info"></i> No Records Found!</h5>
                    No discount records found for the selected criteria. Add some discount data to see records here.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Discount Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_record_id" name="record_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_discount">Discount (%)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_discount" name="discount" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_total_arrears">Jumlah Tunggakan (RM)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_total_arrears" name="total_arrears" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_received_discount">Terimaan Diskaun (RM)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_received_discount" name="received_discount" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_payment">Bayaran Pelajar (RM)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_payment" name="payment" min="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateRecord()">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
function editRecord(recordId) {
    // Get record data and populate modal
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/debt/discountReport/getDiscountRecord') }}/" + recordId,
        method: 'GET',
        success: function(data) {
            if(data.success) {
                $('#edit_record_id').val(data.record.id);
                $('#edit_discount').val(data.record.discount);
                $('#edit_total_arrears').val(data.record.total_arrears);
                $('#edit_received_discount').val(data.record.received_discount);
                $('#edit_payment').val(data.record.payment);
                $('#editModal').modal('show');
            } else {
                alert('Error loading record data');
            }
        },
        error: function(err) {
            alert('Error loading record data');
            console.log(err);
        }
    });
}

function updateRecord() {
    var formData = {
        record_id: $('#edit_record_id').val(),
        discount: $('#edit_discount').val(),
        total_arrears: $('#edit_total_arrears').val(),
        received_discount: $('#edit_received_discount').val(),
        payment: $('#edit_payment').val()
    };

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('finance/debt/discountReport/updateDiscountRecord') }}",
        method: 'POST',
        data: formData,
        success: function(data) {
            if(data.message === 'Success') {
                alert('Record updated successfully!');
                $('#editModal').modal('hide');
                loadDiscountRecords(); // Reload the table
            } else {
                alert('Error: ' + data.message);
            }
        },
        error: function(err) {
            alert('Error updating record');
            console.log(err);
        }
    });
}

function deleteRecord(recordId) {
    if(confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('finance/debt/discountReport/deleteDiscountRecord') }}/" + recordId,
            method: 'DELETE',
            success: function(data) {
                if(data.message === 'Success') {
                    alert('Record deleted successfully!');
                    loadDiscountRecords(); // Reload the table
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function(err) {
                alert('Error deleting record');
                console.log(err);
            }
        });
    }
}
</script> 