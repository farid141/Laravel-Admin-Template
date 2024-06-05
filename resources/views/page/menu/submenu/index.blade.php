@extends('layout')
@section('content')
    <div>
        this is submenu page
    </div>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Parent</th>
                <th>URL</th>
                <th>Order</th>
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
                    url: '{!! route('submenu.index') !!}',
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
                        data: 'menu.name'
                    },
                    {
                        data: 'url'
                    },
                    {
                        data: 'order'
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
                                    data-bs-target="#edit-submenu-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                            const btn_delete =
                                `<form action="" class="d-inline" id="delete-submenu-form" data-id=":id">
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

            // **********************************
            // MODAL EDIT SUBMENU CLICKED
            // **********************************
            // which button is clicked
            // Use event delegation to handle events for dynamically created buttons
            var triggerButton;
            $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                triggerButton = $(this);
            });

            // Handler edit modal showed
            $('#edit-submenu-modal').on('shown.bs.modal', () => {
                var id = triggerButton.data('id');
                var url = "{{ route('submenu.edit', ['submenu' => ':id']) }}".replace(':id', id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $('#edit-submenu-form [name="name"]').val(response.name);
                        $('#edit-submenu-form [name="url"]').val(response.url);
                        $('#edit-submenu-form [name="order"]').val(response.order);
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
            // FORM EDIT SUBMENU SUBMITTED
            // ***************************
            $('#edit-submenu-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = triggerButton.data('id');
                var url = "{{ route('submenu.update', ['submenu' => ':id']) }}".replace(':id', id);
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
                        $("#edit-submenu-modal").modal('hide');
                        dt.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                // label error to the input field 
                                var input = formElement.find('[name="' + key + '"]');
                                input.addClass('is-invalid');

                                // Display all rule that has been violated
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
            // DELETE EDIT SUBMENU SUBMITTED
            // ***************************
            // HARUS EVENT DELEGATION
            $(document).on('submit', '#delete-submenu-form', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var formData = new FormData(this);
                var url = "{{ route('submenu.destroy', ['submenu' => ':id']) }}".replace(':id', id);

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

<!-- Modal Edit -->
<div class="modal fade" id="edit-submenu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Submenu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-submenu-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="menu_id">Menu Name:</label>
                        <select name="menu_id" id="menu_id" class="form-select">
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name">Submenu Name:</label>
                        <input type="text" placeholder="Menu Name" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="url">URL:</label>
                        <input type="text" placeholder="URL" class="form-control" name="url" required>
                    </div>

                    <div class="mb-3">
                        <label for="order">Order:</label>
                        <input type="text" placeholder="Order" class="form-control" name="order" required>
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