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
                        <label for="edit-menu_id">Menu Name:</label>
                        <select name="menu_id" id="edit-menu_id" class="form-select">
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-name">Submenu Name:</label>
                        <input type="text" placeholder="Menu Name" class="form-control" name="name" id="edit-name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-url">URL:</label>
                        <input type="text" placeholder="URL" class="form-control" name="url" id="edit-url"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-order">Order:</label>
                        <input type="text" placeholder="Order" class="form-control" name="order" id="edit-order"
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
