<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle mb-0">
    <thead class="thead-themed">
      <tr>
        <th style="width: 25%;">Position Held</th>
        <th style="width: 30%;">Employer</th>
        <th style="width: 12%;">Year Start</th>
        <th style="width: 12%;">Year End</th>
        <th style="width: 21%;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($experiences as $exp)
        <tr>
          <td>{{ $exp->position }}</td>
          <td>{{ $exp->employer }}</td>
          <td>{{ $exp->year_start }}</td>
          <td>{{ $exp->year_end ?? '-' }}</td>
          <td>
            <button
              type="button"
              class="btn btn-sm btn-outline-primary btn-edit-experience me-2"
              data-toggle="modal"
              data-target="#experienceEditModal"
              data-id="{{ $exp->id }}"
              data-position="{{ $exp->position }}"
              data-employer="{{ $exp->employer }}"
              data-year_start="{{ $exp->year_start }}"
              data-year_end="{{ $exp->year_end ?? '' }}"
            >
              <i class="fa fa-edit me-1"></i>Edit
            </button>
            <button
              type="button"
              class="btn btn-sm btn-outline-danger btn-delete-experience"
              data-id="{{ $exp->id }}"
            >
              <i class="fa fa-trash me-1"></i>Delete
            </button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            No past work experiences added yet.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

