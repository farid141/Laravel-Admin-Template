<div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="edit-user-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">User Name:</label>
                        <input id="edit-name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-role">Role:</label>
                        <select name="role" id="edit-role" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email">Email:</label>
                        <input id="edit-email" type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <input class="form-check-input" type="checkbox" id="check-edit-password">
                        <label class="form-check-label" for="check-edit-password">Edit password?</label>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password">Password:</label>
                        <div class="input-group">
                            <input id="edit-password" type="password" class="form-control password" name="password"
                                disabled>
                            <span class="input-group-text toggle-password" style="cursor: pointer">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password_confirmation">Confirm Password:</label>
                        <div class="input-group">
                            <input id="edit-password_confirmation" type="password" class="form-control password"
                                name="password_confirmation" disabled>
                            <span class="input-group-text toggle-password" style="cursor: pointer">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </span>
                        </div>
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
        $('#edit-user-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).attr('data-id');
            var url = "{{ route('user.update', ['user' => ':id']) }}".replace(':id', id);
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
                    $("#edit-user-modal").modal('hide');
                    dt.ajax.reload(null, false); // reload datatable
                    removeErrorMessages(formElement);
                    emptyForm(formElement);

                    $('#edit-password').val('');
                    $('#edit-password').attr('disabled', true);
                    $('#edit-password_confirmation').val('');
                    $('#edit-password_confirmation').attr('disabled', true);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'edit');
                    } else if (xhr.status === 403) {
                        swal.fire("Error", "Unauthorized Acess.", "error");
                    } else {
                        swal.fire("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Edit user failed',
                        type: 'error'
                    });
                }
            });
        });

        $('#check-edit-password').click(function() {
            if ($(this).prop('checked')) {
                $('#edit-password').removeAttr('disabled');
                $('#edit-password_confirmation').removeAttr('disabled');
            } else {
                $('#edit-password').val('');
                $('#edit-password').attr('disabled', true);
                $('#edit-password_confirmation').val('');
                $('#edit-password_confirmation').attr('disabled', true);
            }
        });
    </script>
@endpush
