<div id="users-section" class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-people-fill me-2" style="color: #d4af37;"></i>User Management</h2>
        <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus-fill"></i> Add User
        </button>
    </div>
    <div class="table-container">
        <table class="table table-hover" style="vertical-align: top;">
            <thead>
                <tr><th class="text-center" style="width: 50px;">No.</th><th>Full Name</th><th>Username</th><th>Email</th><th>Contact</th><th class="text-center" style="width: 60px;">Color</th><th class="text-center" style="width: 100px;">Role</th><th class="text-center" style="width: 100px;">Created At</th><th class="text-center" style="width: 100px;">Actions</th></tr>
            </thead>
            <tbody>
                <?php if($users_result && $users_result->num_rows > 0): ?>
                    <?php $counter = 1; while($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?php echo $counter; ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($user['contact_no'] ?? '-'); ?></td>
                        <td class="text-center"><span class="badge" style="background-color: <?php echo htmlspecialchars($user['color_code'] ?? '#4285F4'); ?>;">&nbsp;</span></td>
                        <td class="text-center"><span class="badge bg-<?php echo $user['role'] == 'super_admin' ? 'danger' : ($user['role'] == 'attorney' ? 'primary' : 'secondary'); ?>"><?php echo $user['role'] == 'super_admin' ? 'Super Admin' : ($user['role'] == 'attorney' ? 'Attorney' : 'Secretary'); ?></span></td>
                        <td class="text-center"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-gold edit-user" data-user='<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES); ?>' title="Edit User"><i class="bi bi-pencil"></i></button>
                                <?php if($user['user_id'] != $_SESSION['user_id']): ?>
                                <a href="?section=users&delete_user=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete this user?" title="Delete User"><i class="bi bi-trash"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php $counter++; endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelectorAll('.edit-user').forEach(btn => {
    btn.addEventListener('click', function() {
        const userData = JSON.parse(this.dataset.user);
        document.getElementById('edit_user_id').value = userData.user_id || '';
        document.getElementById('edit_full_name').value = userData.full_name || '';
        document.getElementById('edit_username').value = userData.username || '';
        document.getElementById('edit_email').value = userData.email || '';
        document.getElementById('edit_contact_no').value = userData.contact_no || '';
        document.getElementById('edit_role').value = userData.role || '';
        document.getElementById('edit_color_code').value = userData.color_code || '#4285F4';
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    });
});
</script>
