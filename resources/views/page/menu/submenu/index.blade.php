@extends('layout')
@section('content')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-submenu-modal">
        Add Submenu
    </button>

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
                    orderable: true,
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
        // Set all element value with ajax data
        $('#edit-submenu-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            $('#edit-submenu-form').attr('data-id', id); //set form's data-id

            var url = "{{ route('submenu.edit', ['submenu' => ':id']) }}".replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $(`#edit-submenu-form [id="edit-menu_id"] option[value="${response.menu.id}"]`)
                        .prop('selected',
                            true);
                    $('#edit-submenu-form [id="edit-name"]').val(response.name);
                    $('#edit-submenu-form [id="edit-url"]').val(response.url);
                    $('#edit-submenu-form [id="edit-order"]').val(response.order);
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
        // DELETE SUBMENU SUBMITTED
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
                                content: 'delete submenu failed',
                                type: 'error'
                            });
                        }
                    });
                }
            });
        });

        function displayValidationErrors(errors, formElement, formName) {
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

@include('page.menu.submenu.partials.create-form')
@include('page.menu.submenu.partials.edit-form')
