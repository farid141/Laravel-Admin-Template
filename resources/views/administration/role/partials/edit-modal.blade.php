<div class="modal fade" id="edit-role-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-role-form" method="POST">
                @csrf
                <div class="modal-body">
                    <input hidden name="_method" required value="PUT">

                    <div class="mb-3">
                        <label for="edit-name">Role Name:</label>
                        <input id="edit-name" type="text" placeholder="Role Name" class="form-control" name="name"
                            required>
                    </div>

                    <div>
                        <label for="">Permissions</label>
                        @foreach ($grouppedPermissions as $permissions)
                            <div class="row ps-3">
                                @foreach ($permissions as $permission)
                                    <div class="form-check col-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="edit-{{ trim($permission) }}" name="permissions[]"
                                            value="{{ $permission }}">
                                        <label class="form-check-label"
                                            for="edit-{{ trim($permission) }}">{{ $permission }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('#edit-role-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).data('id');
            var url = "{{ route('role.update', ['role' => ':id']) }}".replace(':id', id);
            var formElement = $(this);
            removeErrorMessages(formElement);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#edit-role-modal").modal('hide');
                    dt.ajax.reload(null, false); // reload datatable
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'edit');
                    } else {
                        swal("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'create role failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
