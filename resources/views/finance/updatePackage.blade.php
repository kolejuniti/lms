  <div class="modal-header">

  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div>
        <div class="form-group">
            <label class="form-label" for="programs">Program</label>
            <select class="form-select" id="programs" name="programs">
            <option value="-" selected disabled>-</option>
            @foreach ($data['program'] as $prg)
            <option value="{{ $prg->id }}" {{ ($data['package']->program_id == $prg->id) ? 'selected' : '' }}>{{ $prg->progname }}</option>
            @endforeach
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="intakes">Intake</label>
            <select class="form-select" id="intakes" name="intakes">
            <option value="-" selected disabled>-</option>
            @foreach ($data['session'] as $ses)
            <option value="{{ $ses->SessionID }}" {{ ($data['package']->intake_id == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName}}</option> 
            @endforeach
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="semesters">Semester</label>
            <select class="form-select" id="semesters" name="semesters">
            <option value="-" selected disabled>-</option>
            @foreach ($data['semester'] as $ses)
            <option value="{{ $ses->id }}" {{ ($data['package']->semester_id == $ses->id) ? 'selected' : '' }}>{{ $ses->semester_name}}</option> 
            @endforeach
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="claims">Claim Package</label>
            <select class="form-select" id="claims" name="claims">
            <option value="-" selected disabled>-</option>
            @foreach ($data['claim'] as $clm)
            <option value="{{ $clm->id }}" {{ ($data['package']->claim_id == $clm->id) ? 'selected' : '' }}>{{ $clm->name}}</option> 
            @endforeach
            </select>
        </div>
      </div>
      <div class="col-md-6" id="claim-card">
        <div class="form-group">
          <label class="form-label" for="prices">Price per Unit (RM)</label>
          <input type="number" id="prices" name="prices" class="form-control" value="{{ $data['package']->pricePerUnit }}">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
      <div class="form-group pull-right">
          <input type="submit" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit" onclick="add2()">
      </div>
  </div>

<script type="text/javascript">
  function add2()
  {

    var formData = new FormData();

    getInput = {
      program : $('#programs').val(),
      intake : $('#intakes').val(),
      semester : $('#semesters').val(),
      claim : $('#claims').val(),
      price : $('#prices').val(),
    };

    let id = "{{ $data['package']->id }}";
    
    formData.append('addClaim', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "/finance/claimpackage/addclaim?idS=" + id,
        type: 'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        error:function(err){
            console.log(err);
        },
        success:function(res){
            try{
                if(res.message == "Success"){
                    alert("Success! Claim has been updated!");
                    
                    // Start with an empty table structure
                    var newTable = "<table id='table_projectprogress_course' class='table table-striped projects display dataTable no-footer' style='width: 100%;'>" +
                                        "<thead class='thead-themed'>" +
                                        "<tr>" +
                                            "<th style='width: 1%'>No.</th>" +
                                            "<th style='width: 20%'>Claim Name</th>" +
                                            "<th style='width: 5%'>Price Per Unit</th>" +
                                            "<th style='width: 5%'>Program</th>" +
                                            "<th style='width: 5%'>Intake</th>" +
                                            "<th style='width: 5%'>Semester</th>" +
                                            "<th style='width: 20%'></th>" +
                                        "</tr>" +
                                        "</thead>" +
                                        "<tbody>";

                    // Add new rows
                    $.each(res.data, function(i, item) {
                        var newRow = "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + item.name + "</td>" +
                            "<td>" + item.pricePerUnit + "</td>" +
                            "<td>" + item.progname + "</td>" +
                            "<td>" + item.SessionName + "</td>" +
                            "<td>" + item.semester_name + "</td>" +
                            "<td class='project-actions text-right' style='text-align: center;'>" +
                              "<a class='btn btn-info btn-sm pr-2' href='#' onclick='updatePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-pencil-alt'></i> Edit" +
                              "</a>" +
                              "<a class='btn btn-danger btn-sm' href='#' onclick='deletePackage(\"" + item.id + "\")'>" +
                                  "<i class='ti-trash'></i> Delete" +
                              "</a>" +
                            "</td>" +
                        "</tr>";
                        newTable += newRow;
                    });

                    // Close table structure
                    newTable += "</tbody></table>";

                    // Replace the div contents with the new table
                    $('#add-student-div').html(newTable);

                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
                    }
                    else if(res.message == "Group code already existed inside the system"){
                        $('#classcode_error').html(res.message);
                    }
                    else{
                        alert(res.message);
                    }
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });

  }
</script>