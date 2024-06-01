@extends('layout')
@section('content')
    <form action="/menu" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name">Menu Name:</label>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input type="text" placeholder="Menu Name" class="form-control" name="name" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="order">Order:</label>
            @error('order')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input type="text" placeholder="Order" class="form-control" name="order" value="{{ old('order') }}">
        </div>

        <div class="mb-3">
            <input id="icon" name="icon"></input>
            <div class="form-group">
                <i id="selected-icon"></i>
                <a href="#modal-icon" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-icon">
                    Pilih Icon
                </a>
            </div>
        </div>
        <button href="#" class="btn btn-primary rounded-pill">Primary</button>
    </form>

    {{-- Modal Icon --}}
    <div class="modal fade text-left modal-lg" id="modal-icon" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Pilih Icon</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($icons as $icon)
                        <a href="#" onclick="modalIcon('{{ substr($icon->getFilename(), 0, -4) }}')">
                            <i class="bi bi-{{ substr($icon->getFilename(), 0, -4) }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.datatable').DataTable();

        const modalIcon = (icon) => {
            $('#modal-icon').modal('toggle')
            $('#selected-icon').attr('class', `bi bi-${icon}`)
            $('#icon').attr('value', icon)
        }
    </script>
@endpush
