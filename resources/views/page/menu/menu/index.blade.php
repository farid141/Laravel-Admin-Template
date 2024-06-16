@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-menu-modal">
        Add Menu
    </button>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Icon</th>
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
        var editIconpicker = null;
        var createIconpicker = null;

        $(document).ready(function() {
            // Datatable definition
            dt = $('.datatable').DataTable({
                ajax: {
                    url: '{!! route('menu.index') !!}',
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
                        data: 'icon'
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
                                    data-bs-target="#edit-menu-modal" data-id=":id">
                                    <i class="bi bi-pencil"></i>
                                </button>`.replace(':id', row.id);
                            const btn_delete =
                                `<form action="" class="d-inline" id="delete-menu-form" data-id=":id">
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

            // iconpicker
            fetch('/assets/vendors/iconpicker/bootstrap5.json')
                .then(response => response.json())
                .then(result => {
                    // Initialize Iconpicker with the fetched icons
                    editIconpicker = new Iconpicker(document.querySelector("#edit-icon"), {
                        icons: result,
                        showSelectedIn: document.querySelector('#selected-edit-icon'),
                        searchable: true,
                        selectedClass: "selected",
                        containerClass: "my-picker",
                        hideOnSelect: true,
                        fade: true,
                        valueFormat: val => `${val}`
                    });

                    // Initialize Iconpicker with the fetched icons
                    createIiconpicker = new Iconpicker(document.querySelector("#create-icon"), {
                        icons: result,
                        showSelectedIn: document.querySelector('#selected-create-icon'),
                        searchable: true,
                        selectedClass: "selected",
                        containerClass: "my-picker",
                        hideOnSelect: true,
                        fade: true,
                        valueFormat: val => `${val}`
                    });
                })
                .catch(error => console.error('Error fetching the JSON file:', error));

            // ***************************
            // FORM Create menu SUBMITTED
            // ***************************
            $('#create-menu-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var formElement = $(this);

                removeInvalidMessage(formElement);

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
                            displayErrorMessage(errors, formElement, 'create');
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

            // **********************************
            // MODAL EDIT MENU CLICKED
            // **********************************
            // which button is clicked
            // Use event delegation to handle events for dynamically created buttons
            var triggerButton;
            $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                triggerButton = $(this);
            });

            // Set all element value with ajax data
            $('#edit-menu-modal').on('shown.bs.modal', () => {
                var id = triggerButton.data('id');
                var url = "{{ route('menu.edit', ['menu' => ':id']) }}".replace(':id', id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $('#edit-menu-form [id="edit-name"]').val(response.name);
                        $('#edit-menu-form [id="edit-order"]').val(response.order);
                        editIconpicker.set(response.icon);
                        editIconpicker.el.dispatchEvent(new Event('change'));
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
            // FORM EDIT MENU SUBMITTED
            // ***************************
            $('#edit-menu-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = triggerButton.data('id');
                var url = "{{ route('menu.update', ['menu' => ':id']) }}".replace(':id', id);
                var formElement = $(this);

                removeInvalidMessage(formElement);

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
                            content: 'create menu failed',
                            type: 'error'
                        });
                    }
                });
            });

            // ***************************
            // DELETE EDIT MENU SUBMITTED
            // ***************************
            // HARUS EVENT DELEGATION
            $(document).on('submit', '#delete-menu-form', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var formData = new FormData(this);
                var url = "{{ route('menu.destroy', ['menu' => ':id']) }}".replace(':id', id);

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
                                    content: 'delete menu failed',
                                    type: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });

        function displayErrorMessage(errors, formElement, formName) {
            $.each(errors, function(key, value) {
                // add is-invalid class to the corresponsing element 
                var input = formElement.find(`[id="${formName}-${key}"]`);
                input.addClass('is-invalid');

                // Add element contains error message after inputElement
                var errorElement = $(
                    '<div class="invalid-feedback"></div>');
                $.each(value, function(index, message) {
                    errorElement.append(`<div>${message}</div>`);
                });
                input.after(errorElement);
            });
        }

        function removeInvalidMessage(formElement) {
            // Clear previous error message
            formElement.find('.invalid-feedback').remove();
            formElement.find('.form-control').removeClass('is-invalid');
            formElement.find('.form-select').removeClass('is-invalid');
        }
    </script>
@endpush

@include('page.menu.menu.partials.create-form')
@include('page.menu.menu.partials.edit-form')
