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


        // MODAL EDIT MENU SHOWN
        $('#edit-menu-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            var url = "{{ route('menu.edit', ['menu' => ':id']) }}".replace(':id', id);
            $('#edit-menu-form').attr('data-id', id); //set form's data-id

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

        // DELETE EDIT MENU SUBMITTED
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
    </script>
@endpush

@include('administration.menu.partials.create-modal')
@include('administration.menu.partials.edit-modal')
