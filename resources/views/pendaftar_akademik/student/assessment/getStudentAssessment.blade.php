<div class="table-responsive">
  <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
    <div class="row">
      <div class="col-sm-12">
        <table id="myTable" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
          <thead>
            <tr>
              <th style="width: 1%">
                No.
              </th>
              <th>
                Title
              </th>
              <th>
                Groups
              </th>
              <th>
                Chapters
              </th>
              <th>
                Status
              </th>
              <th>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data['assessment'] as $key => $dt)
            <tr>
                <td style="width: 1%">
                    {{ $key+1 }}
                </td>
                <td>
                    {{ $dt->title }}
                </td>
                <td>
                    @foreach ($data['group'][$key] as $grp)
                        Group {{ $grp->groupname }},
                    @endforeach
                </td>
                <td>
                    @foreach ($data['chapter'][$key] as $chp)
                        Chapter {{ $chp->ChapterNo }} : {{ $chp->DrName }},
                    @endforeach
                </td>
                <td>
                    {{ $dt->statusname }}
                </td>
                <td class="project-actions text-right">
                    <a id="student-assessment-btn-{{ $dt->id }}" 
                       class="btn btn-success btn-sm mr-2 student-assessment-btn" 
                       href="#">
                        <i class="ti-user"></i>
                        Students
                    </a>
                </td>
            </tr>                            
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
      // Initialize DataTable
      $('#myTable').DataTable();

      // Handle click event for all buttons with the class 'student-assessment-btn'
      $('.student-assessment-btn').on('click', function (e) {
          e.preventDefault(); // Prevent default behavior

          // Get the current button and its dynamic data-id
          var dtId = $(this).attr('id').split('-')[3]; // Extract the id from button ID
          var intake = $('#intake').val(); // Get the selected intake value

          // // Validate the intake input
          // if (!intake) {
          //     alert('Please select an intake first!');
          //     return;
          // }

          // Construct the URL dynamically
          var url = '/AR/student/studentAssessment/assessmentStatus/' + dtId + '/{{ $data["type"] }}?intake=' + encodeURIComponent(intake);

          // Redirect to the constructed URL
          window.location.href = url;
      });
  });
</script>