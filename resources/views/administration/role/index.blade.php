@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-role-modal">
        Add Role
    </button>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Guard</th>
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
                    url: '{!! route('role.index') !!}',
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
                                    data-bs-target="#edit-role-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                            const btn_delete =
                                `<form action="" class="d-inline" id="delete-role-form" data-id=":id">
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
            // FORM Create role SUBMITTED
            // ***************************
            $('#create-role-form').submit(function(e) {
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
                        $("#create-role-modal").modal('hide');
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
                            content: 'create role failed',
                            type: 'error'
                        });
                    }
                });
            });

            // **********************************
            // MODAL EDIT role CLICKED
            // **********************************
            // which button is clicked
            // Use event delegation to handle events for dynamically created buttons
            var triggerButton;
            $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                triggerButton = $(this);
            });

            // Set all element value with ajax data
            $('#edit-role-modal').on('shown.bs.modal', () => {
                var id = triggerButton.data('id');
                var url = "{{ route('role.edit', ['role' => ':id']) }}".replace(':id', id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $('#edit-role-form [name="name"]').val(response.name);

                        // find all checkboxes and uncheck them
                        $('#edit-role-form :checkbox').prop('checked', false);

                        // check if the data contain the checkbox
                        response.permissions.forEach(function(permission) {
                            $('#edit-role-form #edit-' + permission.name.replace(/\s/g,
                                '\\ ')).prop('checked',
                                true);
                        });
                    },
                    error: function(response) {
                        showToast({
                            content: 'server error',
                            type: 'error'
                        });
                    }
                });
            });

            // ***************************
            // FORM EDIT role SUBMITTED
            // ***************************
            $('#edit-role-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = triggerButton.data('id');
                var url = "{{ route('role.update', ['role' => ':id']) }}".replace(':id', id);
                var formElement = $(this);

                // Clear previous error message
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
                        $("#edit-role-modal").modal('hide');
                        dt.ajax.reload(null, false); // reload datatable
                    },
                    error: function(xhr) {
                        // error laravel validation
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                // add is-invalid class to the corresponsing element 
                                var inputElement = formElement.find('[name="' + key +
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
                            content: 'create role failed',
                            type: 'error'
                        });
                    }
                });
            });


            // ***************************
            // DELETE role SUBMITTED
            // ***************************
            // HARUS EVENT DELEGATION
            $(document).on('submit', '#delete-role-form', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var formData = new FormData(this);
                var url = "{{ route('role.destroy', ['role' => ':id']) }}".replace(':id', id);

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
                                    content: 'delete role failed',
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
<div class="modal fade" id="create-role-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-role-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Role Name:</label>
                        <input id="create-name" type="text" placeholder="Role Name" class="form-control"
                            name="name" required>
                    </div>

                    <div>
                        <label for="">Permissions</label>
                        @foreach ($grouppedPermissions as $permissions)
                            <div class="row ps-3">
                                @foreach ($permissions as $permission)
                                    <div class="form-check col-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="create-{{ trim($permission) }}" name="permissions[]"
                                            value="{{ $permission }}">
                                        <label class="form-check-label"
                                            for="create-{{ trim($permission) }}">{{ $permission }}</label>
                                    </div>
                                @endforeach
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

<!-- Modal Edit -->
<div class="modal fade" id="edit-role-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-role-form" method="POST">
                @csrf
                <div class="modal-body">
                    <input hidden name="_method" required value="PUT">

                    <div class="mb-3">
                        <label for="edit-name">Role Name:</label>
                        <input id="edit-name" type="text" placeholder="Role Name" class="form-control" name="name"
                            required>
                    </div>

                    <div>
                        <label for="">Permissions</label>
                        @foreach ($grouppedPermissions as $permissions)
                            <div class="row ps-3">
                                @foreach ($permissions as $permission)
                                    <div class="form-check col-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="edit-{{ trim($permission) }}" name="permissions[]"
                                            value="{{ $permission }}">
                                        <label class="form-check-label"
                                            for="edit-{{ trim($permission) }}">{{ $permission }}</label>
                                    </div>
                                @endforeach
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
