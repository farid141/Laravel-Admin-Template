<div class="modal fade" id="edit-menu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit menu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="edit-menu-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">Menu Name:</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-order">Order:</label>
                        <input type="number" class="form-control" name="order" id="edit-order" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Icon</span>
                        <input type="text" id="edit-icon" class="form-control iconpicker" aria-label="Icone Picker"
                            aria-describedby="edit-icon" name="icon">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="edit-has_child"
                            name="has_child">
                        <label class="form-check-label" for="edit-has_child">Has Child</label>
                    </div>

                    <div class="mb-3">
                        <label for="edit-url">Url</label>
                        <input type="text" id="edit-url" class="form-control" aria-describedby="edit-url"
                            name="url" disabled>
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
        $('#edit-menu-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).attr('data-id');
            var url = "{{ route('menu.update', ['menu' => ':id']) }}".replace(':id', id);
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
                    $("#edit-menu-modal").modal('hide');
                    dt.ajax.reload(null, false); // reload datatable
                    removeErrorMessages(formElement);
                    emptyForm(formElement);
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessages(errors, formElement, 'edit');
                    } else if (xhr.status === 403) {
                        swal.fire("Error", "Unauthorized Acess.", "error");
                    } else {
                        swal.fire("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Edit menu failed',
                        type: 'error'
                    });
                }
            });
        });

        // checkbox has_child diklik (UX)
        $('#edit-has_child').on('click', function() {
            if ($(this).prop('checked')) {
                $('#edit-url').attr('disabled', true);
                $('#edit-url').val('');
            } else {
                $('#edit-url').attr('disabled', false);
            }
        });
    </script>
@endpush
