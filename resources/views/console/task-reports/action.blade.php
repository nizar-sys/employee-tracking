<div class="d-flex align-items-center gap-2">
    <a href="{{ route('task-reports.edit', $id) }}"
        class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect edit-record" data-id="{{ $id }}"
        data-bs-toggle="tooltip" title="Edit">
        <i class="ri-pencil-line ri-20px"></i>

        <a href="{{ route('task-reports.show', $id) }}"
            class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect show-record"
            data-id="{{ $id }}" data-bs-toggle="tooltip" title="show">
            <i class="ri-eye-line ri-20px"></i>

            <a href="javascript:;"
                class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect delete-record"
                data-id="{{ $id }}" data-bs-toggle="tooltip" title="Delete">
                <i class="ri-delete-bin-7-line ri-20px"></i>
            </a>
</div>
