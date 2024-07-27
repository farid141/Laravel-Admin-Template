<div class="modal fade" id="create-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-user-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">User Name:</label>
                        <input id="create-name" type="text" placeholder="User Name" class="form-control"
                            name="name" required>
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
                        <input id="create-email" type="email" placeholder="Email" class="form-control" name="email"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="create-password">Password:</label>
                        <input id="create-password" type="password" placeholder="Password" class="form-control"
                            name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-password_confirmation">Confirm Password:</label>
                        <input id="create-password_confirmation" type="password" placeholder="Confirm Password"
                            class="form-control" name="password_confirmation" required>
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
                        content: 'Create user failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
