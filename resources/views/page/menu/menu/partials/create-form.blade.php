<!-- Modal create -->
<div class="modal fade" id="create-menu-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Menu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-menu-form" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="create-name">Menu Name:</label>
                        <input type="text" placeholder="Menu Name" class="form-control" name="name"
                            id="create-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="create-order">Order:</label>
                        <input type="text" placeholder="Order" class="form-control" name="order" id="create-order"
                            required>
                    </div>

                    <label>Icon</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text h-100" id="selected-create-icon"></span>
                        </div>
                        <input type="text" class="form-control iconpicker" id="create-icon" name="icon">
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
