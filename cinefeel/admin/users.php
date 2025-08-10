<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

requireAdmin();

$pageTitle = 'Manage Users';
$adminScript = true;

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $userId = (int)$_POST['user_id'];
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $role = sanitize($_POST['role']);
        
        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?");
            $stmt->execute([$name, $email, $role, $userId]);
            setMessage('User updated successfully!', 'success');
        } catch (PDOException $e) {
            setMessage('Failed to update user: ' . $e->getMessage(), 'danger');
        }
    } 
    elseif (isset($_POST['delete_user'])) {
        $userId = (int)$_POST['user_id'];
        
        // Prevent admin from deleting themselves
        if ($userId === $_SESSION['user_id']) {
            setMessage('You cannot delete your own account', 'danger');
        } else {
            try {
                // Delete user favorites first
                $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ?");
                $stmt->execute([$userId]);
                
                // Then delete the user
                $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->execute([$userId]);
                setMessage('User deleted successfully!', 'success');
            } catch (PDOException $e) {
                setMessage('Failed to delete user: ' . $e->getMessage(), 'danger');
            }
        }
    }
    
    redirect('users.php');
}

// Get all users with their favorite counts
$users = $pdo->query("
    SELECT u.*, COUNT(f.favorite_id) as favorite_count
    FROM users u
    LEFT JOIN favorites f ON u.user_id = f.user_id
    GROUP BY u.user_id
    ORDER BY u.created_at DESC
")->fetchAll();

$isAdminPage = true;
require_once '../includes/header.php';
?>

<div class="admin-container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Manage Users</h2>
        </div>
        <div class="card-body">
            <?php displayMessage(); ?>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Favorites</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge <?php echo $user['role'] === 'admin' ? 'bg-primary' : 'bg-secondary'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td><?php echo $user['favorite_count']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-user" 
                                        data-user-id="<?php echo $user['user_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($user['name']); ?>"
                                        data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                        data-role="<?php echo htmlspecialchars($user['role']); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- In admin/movies.php, admin/moods.php, admin/users.php -->
<form method="POST" action="<?php echo BASE_URL; ?>/admin/<?php echo basename($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit buttons
    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            
            document.getElementById('edit_user_id').value = this.dataset.userId;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_role').value = this.dataset.role;
            
            modal.show();
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>