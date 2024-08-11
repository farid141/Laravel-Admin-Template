<!-- Modal create -->
<div class="modal fade" id="create-submenu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Submenu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="create-submenu-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-menu_id">Menu Name:</label>
                        <select name="menu_id" id="create-menu_id" class="form-select">
                            <option value=""></option>
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="create-name">Submenu Name:</label>
                        <input type="text" class="form-control" name="name" id="create-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-url">URL:</label>
                        <input type="text" class="form-control" name="url" id="create-url" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-order">Order:</label>
                        <input type="number" min="0" class="form-control" name="order" id="create-order"
                            required>
                    </div>

                    <label for="permission-name">Permission Name</label>
                    <div class="text-danger m-0">* Don't use '~' character</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" value="create-permission"
                                name="create-permission" id="permission-checkbox">
                        </div>
                        <input id="permission-name" class="form-control" name="permission-name" type="text" disabled>
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
        $('#permission-checkbox').click(function() {
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

        // ***************************
        // FORM Create submenu SUBMITTED
        // ***************************
        $('#create-submenu-form').submit(function(e) {
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
                    $("#create-submenu-modal").modal('hide');
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
                        content: 'Create submenu failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
