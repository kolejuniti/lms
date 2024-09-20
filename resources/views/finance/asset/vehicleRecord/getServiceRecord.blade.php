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
          <label>Service Date</label>
          <input type="date" name="date" id="date" class="form-control">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Odometer (KM)</label>
          <input type="text" name="meter" id="meter" class="form-control">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Copany's Name & Address</label>
          <textarea name="address" id="address" class="form-control" cols="30" rows="10"></textarea>
        </div>
      </div>
      <div>
        <div class="row d-flex mt-2">
          <div class="col-md-4">
            <input type="checkbox" id="MH" class="filled-in" name="MH" value="Minyak HItam">
            <label for="MH">Minyak Hitam</label>
            <textarea name="MHd" id="MHd" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
          <div class="col-md-4">
            <input type="checkbox" id="tayar" class="filled-in" name="tayar" value="Tayar">
            <label for="tayar">Tayar</label>
            <textarea name="tayard" id="tayard" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
          <div class="col-md-4">
            <input type="checkbox" id="belting" class="filled-in" name="belting" value="Belting">
            <label for="belting">Belting</label>
            <textarea name="beltingd" id="beltingd" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
        </div>
      </div>
      <div>
        <div class="row d-flex mt-2">
          <div class="col-md-4">
            <input type="checkbox" id="gearbox" class="filled-in" name="gearbox" value="Gearbox">
            <label for="gearbox">Gearbox</label>
            <textarea name="gearboxd" id="gearboxd" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
          <div class="col-md-4">
            <input type="checkbox" id="brek" class="filled-in" name="brek" value="Brek">
            <label for="brek">Brek</label>
            <textarea name="brekd" id="brekd" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
          <div class="col-md-4">
            <input type="checkbox" id="lainlain" class="filled-in" name="lainlain" value="Lain-Lain">
            <label for="lainlain">Lain-Lain</label>
            <textarea name="lainlaind" id="lainlaind" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
        </div>
      </div>
      <div>
        <div class="row d-flex mt-2">
          <div class="col-md-4">
            <input type="checkbox" id="aircond" class="filled-in" name="aircond" value="Aircond">
            <label for="aircond">Aircond</label>
            <textarea name="aircondd" id="aircondd" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
          <div class="col-md-4">
            <input type="checkbox" id="bateri" class="filled-in" name="bateri" value="Bateri">
            <label for="bateri">Bateri</label>
            <textarea name="baterid" id="baterid" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
        </div>
      </div>
      <div>
        <div class="row d-flex mt-2">
          <div class="col-md-4">
            <input type="checkbox" id="lampu" class="filled-in" name="lampu" value="Lampu">
            <label for="lampu">Lampu</label>
            <textarea name="lampud" id="lampud" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
          <div class="col-md-4">
            <input type="checkbox" id="SA" class="filled-in" name="SA" value="Shock Absorber">
            <label for="SA">Shock Absorber</label>
            <textarea name="SAd" id="SAd" class="form-control" cols="4" rows="3" hidden></textarea>
          </div>
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Amount (RM)</label>
          <input type="number" name="amount" id="amount" class="form-control">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Notes</label>
          <textarea name="note" id="note" class="form-control" cols="30" rows="10"></textarea>
        </div>
      </div>
      <div class="form-group pull-right">
        <input type="submit" name="submitService" class="btn btn-primary pull-right" value="submit" onclick="storeService('{{$id}}')">
      </div>
    </div>
  </div>
  <div id="service_list">
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

<script>
  $('.filled-in').change(function() {
      let textareaId = `#${this.id}d`; // Use 'id' to match the textarea
      if(this.checked) {
          $(textareaId).removeAttr('hidden'); // Show the textarea
      } else {
          $(textareaId).attr('hidden', true); // Hide the textarea when unchecked
      }
  });
</script>