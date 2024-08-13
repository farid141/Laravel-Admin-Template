<div class="modal fade" id="create-menu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Menu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="create-menu-form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Menu Name:</label>
                        <input type="text" class="form-control" name="name" id="create-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-order">Order:</label>
                        <input type="number" class="form-control" name="order" id="create-order" required>
                    </div>

                    <div class="input-group mb-3" data-bs-theme="light">
                        <span class="input-group-text">Icon</span>
                        <input type="text" id="create-icon" class="form-control iconpicker" aria-label="Icone Picker"
                            aria-describedby="create-icon" name="icon" data-bs-theme="light">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="create-has_child"
                            name="has_child">
                        <label class="form-check-label" for="create-has_child">Has Child</label>
                    </div>

                    <div class="mb-3">
                        <label for="create-url">Url</label>
                        <input type="text" id="create-url" class="form-control" aria-describedby="create-url"
                            name="url">
                    </div>

                    <div class="mb-3">
                        <input class="form-check-input" type="checkbox" name="create-permission" id="create-permission">
                        <label class="form-check-label" for="create-permission">Create Permission?</label>
                    </div>

                    <div class="mb-3">
                        <label for="permission-name">Permission Name:</label>
                        <div class="text-danger m-0">* Don't use '~' character</div>
                        <div class="text-danger m-0">* format: Menu-Submenu</div>
                        <input type="text" class="form-control" name="permission-name" id="permission-name" disabled>
                    </div>

                    <div class="mb3" id="permission-list">
                        @foreach ($permissions as $permission)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox"
                                    id="{{ $permission }}-permission-checkbox" name="permissions[]"
                                    value="{{ $permission }}" disabled>
                                <label class="form-check-label"
                                    for="{{ $permission }}-permission-checkbox">{{ $permission }}</label>
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
        $('#create-menu-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var formElement = $(this);

            $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#create-menu-modal").modal('hide');
                    dt.ajax.reload(null, false); // refresh datatable
                    removeErrorMessages(formElement);
                    emptyForm(formElement);
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'create');
                    } else if (xhr.status === 403) {
                        swal.fire("Error", "Unauthorized Acess.", "error");
                    } else {
                        swal.fire("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Create menu failed',
                        type: 'error'
                    });
                }
            });
        });

        $('#create-permission').click(function() {
            if ($(this).prop('checked')) {
                $('#permission-name').removeAttr('disabled');
                $('#permission-list .form-check-input').attr('disabled', false);
            } else {
                $('#permission-name').val('');
                $('#permission-name').attr('disabled', true);
                $('#permission-list .form-check-input').prop('checked', false);
                $('#permission-list .form-check-input').attr('disabled', true);
            }
        });

        $('#create-has_child').on('click', function() {
            if ($(this).prop('checked')) {
                $('#create-url').attr('disabled', true);
                $('#create-url').val('');
            } else {
                $('#create-url').attr('disabled', false);
            }
        });
    </script>
@endpush
