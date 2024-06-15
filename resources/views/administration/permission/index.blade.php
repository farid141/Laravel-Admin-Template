@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-permission-modal">
        Add Permission
    </button>
    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        var dt = null;

        $(document).ready(function() {
            // Datatable definition
            dt = $('.datatable').DataTable({
                ajax: {
                    url: '{!! route('permission.index') !!}',
                    dataSrc: ''
                },
                columns: [{
                        data: null,
                        width: 20,
                        render: (data, type, row, meta) => {
                            return meta.row + 1;
                        },
                        orderable: false,
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'guard_name'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'updated_at'
                    },
                    {
                        data: 'id',
                        render: (data, type, row, meta) => {
                            const btn_edit =
                                `<button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#edit-permission-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                            const btn_delete =
                                `<form action="" class="d-inline" id="delete-permission-form" data-id=":id">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn icon btn-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>`.replace(':id', row.id);
                            return `${btn_edit} ${btn_delete}`;
                        },
                    },
                ]
            });

            // ***************************
            // FORM Create Permission SUBMITTED
            // ***************************
            $('#create-permission-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var formElement = $(this);

                // Clear previous error message
                formElement.find('.invalid-feedback').remove();
                formElement.find('.form-control').removeClass('is-invalid');
                formElement.find('.form-select').removeClass('is-invalid');

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
                    },
                    error: function(xhr) {
                        // error laravel validation
                        if (xhr.status == 422) {
                            let errors = xhr.responseJSON.errors;
                            // the response will be key value of name and message
                            $.each(errors, function(key, value) {
                                // add is-invalid class to the corresponsing element 
                                var inputElement = formElement.find('[id="create-' +
                                    key +
                                    '"]');
                                inputElement.addClass('is-invalid');

                                // Add element contains error message after inputElement
                                var errorElement = $(
                                    '<div class="invalid-feedback"></div>');
                                $.each(value, function(index, message) {
                                    errorElement.append('<div>' + message +
                                        '</div>');
                                });
                                inputElement.after(errorElement);
                            });
                        } else {
                            swal("Error", "An unexpected error occurred.", "error");
                        }

                        showToast({
                            content: 'create permission failed',
                            type: 'error'
                        });
                    }
                });
            });

            // **********************************
            // MODAL EDIT PERMISSION CLICKED
            // **********************************
            // which button is clicked
            // Use event delegation to handle events for dynamically created buttons
            var triggerButton;
            $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                triggerButton = $(this);
            });

            // Handler edit modal showed
            $('#edit-permission-modal').on('shown.bs.modal', () => {
                var id = triggerButton.data('id');
                var url = "{{ route('permission.edit', ['permission' => ':id']) }}".replace(':id', id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $('#edit-permission-form [name="name"]').val(response.name);
                    },
                    error: function(response) {
                        showToast({
                            content: 'server error menu failed',
                            type: 'error'
                        });
                    }
                });
            });

            // ***************************
            // FORM EDIT permission SUBMITTED
            // ***************************
            $('#edit-permission-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = triggerButton.data('id');
                var url = "{{ route('permission.update', ['permission' => ':id']) }}".replace(':id', id);
                var formElement = $(this);

                // Clear previous errors
                formElement.find('.invalid-feedback').remove();
                formElement.find('.form-control').removeClass('is-invalid');
                formElement.find('.form-select').removeClass('is-invalid');

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
                    },
                    error: function(xhr) {
                        // error laravel validation
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                // add is-invalid class to the corresponsing element
                                var input = formElement.find('[name="' + key + '"]');
                                input.addClass('is-invalid');

                                // Add element contains error message after inputElement
                                var errorElement = $(
                                    '<div class="invalid-feedback"></div>');
                                $.each(value, function(index, message) {
                                    errorElement.append('<div>' + message +
                                        '</div>');
                                });
                                input.after(errorElement);
                            });
                        } else {
                            swal("Error", "An unexpected error occurred.", "error");
                        }

                        showToast({
                            content: 'create menu failed',
                            type: 'error'
                        });
                    }
                });
            });

            // ***************************
            // DELETE EDIT permission SUBMITTED
            // ***************************
            // HARUS EVENT DELEGATION
            $(document).on('submit', '#delete-permission-form', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var formData = new FormData(this);
                var url = "{{ route('permission.destroy', ['permission' => ':id']) }}".replace(':id', id);

                confirmationModal().then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                showToast(data);
                                dt.ajax.reload(null, false);
                            },
                            error: function(data) {
                                showToast({
                                    content: 'create menu failed',
                                    type: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

<!-- Modal Create -->
<div class="modal fade" id="create-permission-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Permission</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-permission-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Permission Name:</label>
                        <input id="create-name" type="text" placeholder="Permission Name" class="form-control"
                            name="name" required>
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

<!-- Modal Edit -->
<div class="modal fade" id="edit-permission-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit permission</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-permission-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name">Permission Name:</label>
                            <input id="edit-name" type="text" placeholder="Permission Name" class="form-control"
                                name="name" required>
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
