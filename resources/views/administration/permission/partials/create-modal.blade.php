<!-- Modal Create -->
<div class="modal fade" id="create-permission-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Permission</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" id="create-permission-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Permission Name:</label>
                        <input id="create-name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="input-group mb3" id="permission-group">
                        @foreach ($actions as $action)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="{{ $action }}-action-checkbox"
                                    name="actions[]" value="{{ $action }}">
                                <label class="form-check-label"
                                    for="{{ $action }}-action-checkbox">{{ $action }}</label>
                            </div>
                        @endforeach
                        {{-- custom action --}}
                        <div class="input-group-text">
                            <input class="form-check-input form-check-inline" type="checkbox" value="create-action"
                                name="create-action" id="action-checkbox">
                        </div>
                        <input id="action-name" class="form-control" name="action-name" type="text" disabled>
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
        $('#action-checkbox').click(function() {
            if ($(this).prop('checked')) { // additional action checked
                $('#action-name').removeAttr('disabled');
            } else { //unchecked
                $('#action-name').val('');
                $('#action-name').attr('disabled', true);
            }
        });

        $('#create-permission-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData();
            var formElement = $(this);

            // Get the permission name
            var permissionName = $('#create-name').val();

            // Collect selected permissions
            var selectedPermissions = [];
            $('#permission-group .form-check-input:checked').each(function() {
                if (this.id == 'action-checkbox')
                    formData.append('permissions[]', $('#action-name').val() + '~' + permissionName);
                else
                    formData.append('permissions[]', $(this).val() + '~' + permissionName);
            });

            // Append selected permissions to formData
            formData.append('_token', $('input[name="_token"]').val());
            removeErrorMessages(formElement);

            $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    showToast(data);
                    $("#create-permission-modal").modal('hide');
                    dt.ajax.reload(null, false); // refresh datatable
                    emptyForm(formElement);
                    removeErrorMessages(formElement);
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
                        content: 'Create permission failed',
                        type: 'error'
                    });
                }
            });
        });
    </script>
@endpush
