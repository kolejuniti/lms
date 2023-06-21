

<!-- form start -->
    <table class="w-100 table table-bordered display margin-top-10 w-p100">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th>
                    Date Key-In
                </th>
                <th>
                    Date Posting
                </th>
                <th>
                    Name
                </th>
                <th>
                    Faculty
                </th>
                <th>
                    Channel
                </th>
                <th>
                    Title
                </th>
                <th style="width: 10%">
                    Link
                </th>
                <th>
                    Type
                </th>
                <th>
                    Status
                </th>
                <th>
                    Total View
                </th>
                <th>
                    Total Comment
                </th>
                <th>
                    Total Like
                </th>
                <th>
                    Total Share
                </th>
                <th>
                    Latest Update
                </th>
            </tr>
        </thead>
        <tbody id="table">
            @foreach ($data['post'] as $key=> $pst)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <th>
                  {{ $pst->date }}
                </td>
                <th>
                  {{ $pst->post_date }}
                </td>
                <th>
                  {{ $pst->name }}
                </td>
                <th>
                  {{ $pst->facultyname }}
                </td>
                <th>
                  {{ $pst->channel }}
                </td>
                <th>
                  {{ $pst->title }}
                </td>
                <th class="compact-cell">
                  <a href="{{ $pst->link }}" target="_blank" class="short-link">{{ $pst->link }}</a>
                </th>
                <th>
                  {{ $pst->channel_type }}
                </td>
                <th>
                  {{ $pst->status }}
                </td>
                <th>
                  {{ $pst->total_view }}
                </td>
                <th>
                  {{ $pst->total_comment }}
                </td>
                <th>
                  {{ $pst->total_like }}
                </td>
                <th>
                  {{ $pst->total_share }}
                </td>
                <th>
                  {{ $pst->update_view }}
                </td>
              </tr>
            @endforeach 
        </tbody>
    </table>
              
    
