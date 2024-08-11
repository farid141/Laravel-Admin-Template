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

    @include('administration.user.partials.create-modal')
    @include('administration.user.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        var dt = null;
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

        // MODAL EDIT USER SHOWN
        $('#edit-user-modal').on('shown.bs.modal', (e) => {
            var id = $(e.relatedTarget).data('id');
            var url = "{{ route('user.edit', ['user' => ':id']) }}".replace(':id', id);
            $('#edit-user-form').attr('data-id', id); //set form's data-id

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
                    if (xhr.status === 403) {
                        swal("Error", "Unauthorized Acess.", "error");
                    } else {
                        swal("Error", "Unexpected Error.", "error");
                    }
                    showToast({
                        content: 'server error',
                        type: 'error'
                    });
                }
            });
        });

        // DELETE USER SUBMITTED
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
    </script>
@endpush
