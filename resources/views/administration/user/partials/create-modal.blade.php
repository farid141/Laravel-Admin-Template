<div class="modal fade" id="create-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="create-user-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">User Name:</label>
                        <input id="create-name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-role">Role:</label>
                        <select name="role" id="create-role" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-email">Email:</label>
                        <input id="create-email" type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-password">Password:</label>
                        <div class="input-group">
                            <input id="create-password" type="password" class="form-control password" name="password"
                                required>
                            <span class="input-group-text toggle-password" style="cursor: pointer">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="create-password_confirmation">Confirm Password:</label>
                        <div class="input-group">
                            <input id="create-password_confirmation" type="password" class="form-control password"
                                name="password_confirmation" required>
                            <span class="input-group-text toggle-password" style="cursor: pointer">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#create-user-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var formElement = $(this);
            removeErrorMessages(formElement);

            $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#create-user-modal").modal('hide');
                    dt.ajax.reload(null, false); // refresh datatable
                    removeErrorMessages(formElement);
                    emptyForm(formElement);
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'create');
                    } else if (xhr.status === 403) {
                        swal.fire("Error", "Unauthorized Acess.", "error");
                    } else {
                        swal.fire("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Create user failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
