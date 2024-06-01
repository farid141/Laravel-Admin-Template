@extends('layout')
@section('content')
    <a href="/menu/create" type="button" class="btn btn-primary">Tambah</a>
    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Order</th>
                <th>Actions</th>
                <th>Created at</th>
                <th>Updated at</th>
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
                            const btn_info =
                                `<a href="{{ route('menu.show', ['menu' => ':id']) }}" class="btn icon btn-primary"><i
                                        class="bi bi-info-circle"></i></a>`.replace(':id', row.id);
                            const btn_edit =
                                `<a href="{{ route('menu.edit', ['menu' => ':id']) }}" class="btn icon btn-warning"><i class="bi bi-pencil"></i></a>
                                `.replace(':id', row.id);
                            const btn_delete =
                                `<form action="" class="d-inline" id="delete-menu-form" data-id=":id">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn icon btn-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>`.replace(':id', row.id);
                            return `${btn_info} ${btn_edit} ${btn_delete}`;
                        },
                    },
                ]
            });


            // ***************************
            // DELETE EDIT SUBMENU SUBMITTED
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
    </script>
@endpush
