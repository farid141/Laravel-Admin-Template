<!-- Modal Edit -->
<div class="modal fade" id="edit-permission-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit permission</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="edit-permission-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name">Permission Name:</label>
                            <input id="edit-name" type="text" class="form-control" name="name" required>
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
        $('#edit-permission-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $(this).attr('data-id');
            var url = "{{ route('permission.update', ['permission' => ':id']) }}".replace(':id', id);
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
                    $("#edit-permission-modal").modal('hide');
                    dt.ajax.reload(null, false);
                    emptyForm(formElement);
                    removeErrorMessages(formElement);
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
                        content: 'Edit permission failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
