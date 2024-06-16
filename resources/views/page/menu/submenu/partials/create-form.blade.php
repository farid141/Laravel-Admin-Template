<!-- Modal create -->
<div class="modal fade" id="create-submenu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Submenu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-submenu-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-menu_id">Menu Name:</label>
                        <select name="menu_id" id="create-menu_id" class="form-select">
                            @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="create-name">Submenu Name:</label>
                        <input type="text" placeholder="Menu Name" class="form-control" name="name"
                            id="create-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-url">URL:</label>
                        <input type="text" placeholder="URL" class="form-control" name="url" id="create-url"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="create-order">Order:</label>
                        <input type="text" placeholder="Order" class="form-control" name="order" id="create-order"
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
