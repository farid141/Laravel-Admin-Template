<div class="modal fade" id="create-menu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
            removeErrorMessages(formElement);

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
                    emptyForm(formElement);
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'create');
                    } else {
                        swal("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Create menu failed',
                        type: 'error'
                    });
                }
            });
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
