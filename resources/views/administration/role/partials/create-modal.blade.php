<div class="modal fade" id="create-role-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-role-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Role Name:</label>
                        <input id="create-name" type="text" placeholder="Role Name" class="form-control"
                            name="name" required>
                    </div>

                    <div>
                        <label for="">Permissions</label>
                        @foreach ($grouppedPermissions as $permissions)
                            <div class="row ps-3">
                                @foreach ($permissions as $permission)
                                    <div class="form-check col-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="create-{{ trim($permission) }}" name="permissions[]"
                                            value="{{ $permission }}">
                                        <label class="form-check-label"
                                            for="create-{{ trim($permission) }}">{{ $permission }}</label>
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
        $('#create-role-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var formElement = $(this);
            removeErrorMessages();

            $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#create-role-modal").modal('hide');
                    dt.ajax.reload(null, false); // refresh datatable
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status == 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'create');
                    } else {
                        swal("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Create role failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
