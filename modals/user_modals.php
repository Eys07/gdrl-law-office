<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=users">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name *</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username *</label>
                        <input type="text" name="username" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password *</label>
                        <input type="password" name="password" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number</label>
                        <input type="text" name="contact_no" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role *</label>
                        <select name="role" class="form-select form-select-sm" required>
                            <option value="secretary">Secretary</option>
                            <option value="attorney">Attorney</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Calendar Color (for Attorneys)</label>
                        <input type="color" name="color_code" class="form-control form-control-color" value="#4285F4" style="height: 38px; width: 100%;">
                        <small class="text-muted">This color will appear in the schedule calendar</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_user" class="btn btn-gold">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?section=users">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name *</label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username *</label>
                        <input type="text" name="username" id="edit_username" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number</label>
                        <input type="text" name="contact_no" id="edit_contact_no" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role *</label>
                        <select name="role" id="edit_role" class="form-select form-control-sm" required>
                            <option value="secretary">Secretary</option>
                            <option value="attorney">Attorney</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Calendar Color (for Attorneys)</label>
                        <input type="color" name="color_code" id="edit_color_code" class="form-control form-control-color" value="#4285F4" style="height: 38px; width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_user" class="btn btn-gold">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
