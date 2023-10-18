<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Student Info</b>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Student Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                    </div>
                    <div class="form-group">
                        <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                    </div>
                    <div class="form-group">
                        <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                    </div>
                    <div class="form-group">
                        <p>Intake &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake_name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                    </div>
                    <div class="form-group">
                        <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->semester }}</p>
                    </div>
                    <div class="form-group">
                        <p>Session &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3" id="stud_info">
        <div class="card-header">
            <b>Voucher Details</b>
        </div>
        @if(Session::has('success'))
          <div class="form-group">
              <div class="alert alert-success">
                  <span>{{ Session::get('success') }}</span>
              </div>
          </div>
        @elseif(Session::has('error'))
            <div class="form-group">
                <div class="alert alert-danger">
                    <span>{{ Session::get('error') }}</span>
                </div>
            </div>
        @endif
        <div class="card-body">
            <div class="row">
                <div class="form-group">
                    <b>No. Vouchar</b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="from">From</label>
                        <input type="text" class="form-control" onkeypress="return event.charCode != 32" name="from" id="from">
                    </div>
                </div>
                <div class="col-md-3" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="to">To</label>
                        <input type="text" class="form-control" onkeypress="return event.charCode != 32" name="to" id="to">
                    </div>
                </div> 
                <div class="col-md-2" id="voucher-card">
                    <div class="form-group">
                        <label class="form-label" for="voucher">Total Voucher</label>
                        <input type="number" class="form-control" name="voucher" id="voucher" value="0" readonly>
                    </div>
                </div> 
            </div>
            <div class="col-md-6" hidden>
                <input type="text" class="form-control" name="ic" id="ic" value="{{ $data['student']->ic }}">
            </div> 
            <div class="row">
                <div class="col-md-2" id="amount-card">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount (RM)</label>
                        <input type="number" class="form-control" name="amount" id="amount">
                    </div>
                </div> 
                <div class="col-md-2" id="total-card">
                    <div class="form-group">
                        <label class="form-label" for="total">Total Amount (RM)</label>
                        <input type="number" class="form-control" name="total" id="total" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2" id="expired-card">
                    <div class="form-group">
                        <label class="form-label" for="expired">Expiry Date</label>
                        <input type="date" class="form-control" name="expired" id="expired">
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="add('{{ $data['student']->ic }}')">Add</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <label class="form-label">Vouchar List</label>
                        <table class="w-100 table table-bordered display margin-top-10 w-p100" id="voucher_table">
                            <thead id="voucher_list">
                                <tr>
                                    <th style="width: 1%">
                                        No.
                                    </th>
                                    <th>
                                        No. Vouchar
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Pickup Date
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['voucher'] as $key=> $vcr)
                                  <tr>
                                    <td style="width: 1%">
                                      {{ $key+1 }}
                                    </td>
                                    <th style="width: 15%">
                                      {{ $vcr->no_voucher }}
                                    </td>
                                    <th style="width: 15%">
                                      {{ $vcr->amount }}
                                    </td>
                                    <th style="width: 15%">
                                      {{ $vcr->pickup_date }}
                                    </td>
                                    <th style="width: 20%">
                                      {{ $vcr->name }}
                                    </td>
                                    <th>
                                        <a class="btn btn-success btn-sm" href="#" onclick="claimVoucher('{{ $vcr->id }}')">
                                            <i class="ti-check">
                                            </i>
                                            Claim
                                        </a>
                                        <a class="btn btn-warning btn-sm" href="#" onclick="unclaimVoucher('{{ $vcr->id }}')">
                                            <i class="ti-close">
                                            </i>
                                            Un-Claim
                                        </a>
                                        @if($vcr->name != 'SAH')
                                        <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('{{ $vcr->id }}')">
                                            <i class="ti-trash">
                                            </i>
                                            Delete
                                        </a>
                                        @endif
                                    </td>
                                  </tr>
                                @endforeach 
                                  <tfoot>
                                    <tr>
                                        <td style="width: 1%">
                                        
                                        </td>
                                        <td style="width: 15%">
                                        TOTAL AMOUNT  :
                                        </td>
                                        <td style="width: 15%">
                                        {{ $data['sum'] }}
                                        </td>
                                        <td style="width: 15%">

                                        </td>
                                        <td style="width: 20%">
                                        
                                        </td>
                                        <td>
                                    
                                        </td>
                                    </tr>
                                  </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Declare from and to as global variables
        let from = '';
        let to = '';
        let amount = 0;
        let total_voucher = 0;
        

        $('#from').on('keyup', function(){

            from = $(this).val();
            totalvoucher(from,to);

        });

        $('#to').on('keyup', function(){

            to = $(this).val();
            totalvoucher(from,to);
    

        });

        $('#amount').on('keyup', function(){

            amount = $(this).val();
            totalAmount(amount);
         

        });

        function totalvoucher(from,to)
        {
            total_voucher = 0;

            if(from && to)
            {
                // Remove non-digit characters from from and to
                var lastFrom = from.replace(/\D/g, '');

                // Remove non-digit characters from from and to
                var lastTo = to.replace(/\D/g, '');

                // Try to convert the last character to an integer.
                var numFrom = parseInt(lastFrom);

                // Try to convert the last character to an integer.
                var numTo = parseInt(lastTo);

                if(!isNaN(numFrom) && !isNaN(numTo)) {
                    for(let i = numFrom; i <= numTo; i++) {
                        total_voucher++;
                    }
                }

            }

            $('#voucher').val(total_voucher);
            totalAmount(amount);

        }

        function totalAmount(amount)
        {

            let totalAmount = 0;

            if(amount)
            {

                totalAmount = amount * total_voucher;

            }

            $('#total').val(totalAmount);

        }

    </script>
