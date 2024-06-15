@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-user-modal">
        Add User
    </button>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
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
                    url: '{!! route('user.index') !!}',
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
                        data: 'email'
                    },
                    {
                        data: 'roles[0].name'
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
                                    data-bs-target="#edit-user-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                            const btn_delete =
                                `<form action="" class="d-inline delete-user-form" data-id=":id">
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
            // FORM Create user SUBMITTED
            // ***************************
            $('#create-user-form').submit(function(e) {
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
                        $("#create-user-modal").modal('hide');
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
                            content: 'create user failed',
                            type: 'error'
                        });
                    }
                });
            });

            // **********************************
            // MODAL EDIT user CLICKED
            // **********************************
            // which button is clicked
            // Use event delegation to handle events for dynamically created buttons
            var triggerButton;
            $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                triggerButton = $(this);
            });

            // Set all element value with ajax data
            $('#edit-user-modal').on('shown.bs.modal', () => {
                var id = triggerButton.data('id');
                console.log('test');
                var url = "{{ route('user.edit', ['user' => ':id']) }}".replace(':id', id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $('#edit-user-form [id="edit-name"]').val(response.name);
                        $('#edit-user-form [id="edit-email"]').val(response.email);
                        $('#edit-user-form [id="edit-role"] option[value="' + response.roles[
                                0].name +
                            '"]').prop('selected',
                            true);
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
            // FORM EDIT user SUBMITTED
            // ***************************
            $('#edit-user-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = triggerButton.data('id');
                var url = "{{ route('user.update', ['user' => ':id']) }}".replace(':id', id);
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
                        $("#edit-user-modal").modal('hide');
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
                            content: 'create user failed',
                            type: 'error'
                        });
                    }
                });
            });


            // ***************************
            // DELETE user SUBMITTED
            // ***************************
            // HARUS EVENT DELEGATION
            $(document).on('submit', '.delete-user-form', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var formData = new FormData(this);
                var url = "{{ route('user.destroy', ['user' => ':id']) }}".replace(':id', id);

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
                                    content: 'delete user failed',
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
<div class="modal fade" id="create-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-user-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">User Name:</label>
                        <input id="create-name" type="text" placeholder="User Name" class="form-control"
                            name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-role">Role:</label>
                        <select name="role" id="create-role" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-email">Email:</label>
                        <input id="create-email" type="email" placeholder="Email" class="form-control" name="email"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="create-password">Password:</label>
                        <input id="create-password" type="password" placeholder="Password" class="form-control"
                            name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-password_confirmation">Confirm Password:</label>
                        <input id="create-password_confirmation" type="password" placeholder="Confirm Password"
                            class="form-control" name="password_confirmation" required>
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

<!-- Modal edit -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">edit user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-user-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">User Name:</label>
                        <input id="edit-name" type="text" placeholder="User Name" class="form-control" name="name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-role">Role:</label>
                        <select name="role" id="edit-role" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email">Email:</label>
                        <input id="edit-email" type="email" placeholder="Email" class="form-control" name="email"
                            required>
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
