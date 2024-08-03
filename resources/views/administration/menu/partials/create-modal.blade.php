<div class="modal fade" id="create-menu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Menu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-menu-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Menu Name:</label>
                        <input type="text" placeholder="Menu Name" class="form-control" name="name"
                            id="create-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-order">Order:</label>
                        <input type="number" placeholder="Order" class="form-control" name="order" id="create-order"
                            required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Icon</span>
                        <input type="text" id="create-icon" class="form-control iconpicker" placeholder="Icon"
                            aria-label="Icone Picker" aria-describedby="create-icon" name="icon">
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="create-has_child"
                            name="has_child">
                        <label class="form-check-label" for="create-has_child">Has Child</label>
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

            // Manually handle the checkboxes
            formElement.find('input[type=checkbox]').each(function() {
                if (!this.checked) {
                    formData.append(this.name, '0');
                } else {
                    formData.append(this.name, '1');
                }
            });

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
    </script>
@endpush
