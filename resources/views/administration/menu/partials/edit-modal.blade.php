<div class="modal fade" id="edit-menu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit menu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-menu-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">menu Name:</label>
                        <input type="text" placeholder="Menu Name" class="form-control" name="name" id="edit-name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-order">Order:</label>
                        <input type="text" placeholder="Order" class="form-control" name="order" id="edit-order"
                            required>
                    </div>

                    <label>Icon</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text h-100 " id="selected-edit-icon"></span>
                        </div>
                        <input type="text" class="form-control iconpicker" id="edit-icon" name="icon">
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
        $('#edit-menu-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).data('id');
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
                },
                error: function(xhr) {
                    // error laravel validation
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        displayErrorMessage(errors, formElement, 'edit');
                    } else {
                        swal("Error", "An unexpected error occurred.", "error");
                    }

                    showToast({
                        content: 'Edit menu failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
