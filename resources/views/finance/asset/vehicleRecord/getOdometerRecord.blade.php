<div>
  <div class="modal-header">
      <div class="">
          <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
              &times;
          </button>
      </div>
  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div>
        <div class="form-group">
          <label>Odometer (KM)</label>
          <input type="text" name="odometer" id="odometer" class="form-control">
        </div>
      </div>
      <div class="form-group pull-right">
        <input type="submit" name="submitService" class="btn btn-primary pull-right" value="submit" onclick="storeOdometer('{{$id}}')">
      </div>
    </div>
  </div>
  <div id="odometer_list">
    <div class="card mb-3" id="stud_info">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td style="width: 25%">Service Date</td>
                  <td style="width: 10%">27/06/2024</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td style="width: 25%">Odometer (KM)</td>
                  <td style="width: 10%">27</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="row">
          <div class="col-md-12">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td style="width: 2%">Company's Name & Address</td>
                  <td style="width: 8%">KIAN SENG NO.755, 1ST MILE, JALAN PANTAI, 71000, PORT DICKSON, NEGERI SEMBILAN</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="row">
          <div class="col-md-12">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td style="width: 2%">Service Type</td>
                  <td style="width: 6.7%">Tayar - 4 Tayar <br> Lain-Lain - VALVE RUBBER BRAKE PAD
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="row">
          <div class="col-md-12">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td style="width: 2%">Amount (RM)</td>
                  <td style="width: 6.7%">1476.00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="row">
          <div class="col-md-12">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td style="width: 2%">Note</td>
                  <td style="width: 6%">Note</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="form-group pull-right">
          <input type="submit" name="submitService" class="btn btn-warning pull-right" value="Delete" onclick="">
        </div>
      </div>
    </div>
  </div>
  
  </div>
  <div class="modal-footer">
      
  </div>
</div>