<?php
require_once 'db.php';
require_once 'auth.php';
requireAdmin();

$message = '';
$messageType = '';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? 0;
    
    try {
        if ($action === 'update_status') {
            $status = $_POST['status'] ?? 'active';
            $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND id != ?");
            $stmt->execute([$status, $user_id, $_SESSION['user_id']]);
            $message = 'User status updated successfully';
            $messageType = 'success';
            logActivity($pdo, $_SESSION['user_id'], 'update_user_status', "Updated user $user_id to $status");
        } elseif ($action === 'update_role') {
            $role = $_POST['role'] ?? 'user';
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ? AND id != ?");
            $stmt->execute([$role, $user_id, $_SESSION['user_id']]);
            $message = 'User role updated successfully';
            $messageType = 'success';
            logActivity($pdo, $_SESSION['user_id'], 'update_user_role', "Updated user $user_id to $role");
        } elseif ($action === 'delete_user') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND id != ?");
            $stmt->execute([$user_id, $_SESSION['user_id']]);
            $message = 'User deleted successfully';
            $messageType = 'success';
            logActivity($pdo, $_SESSION['user_id'], 'delete_user', "Deleted user $user_id");
        }
    } catch(PDOException $e) {
        $message = 'Action failed: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Get all users
$users = [];
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch(PDOException $e) {
    $message = 'Failed to load users';
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Management · Mafuya Solution</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <style>
        .user-management {
            padding: 1rem 0;
        }
        .user-management h2 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .user-management h2 i {
            color: var(--gold);
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-table th {
            text-align: left;
            padding: 0.8rem 1rem;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-light);
        }
        .user-table td {
            padding: 0.8rem 1rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-light);
        }
        .user-table tr:hover {
            background: rgba(201,168,76,0.04);
        }
        .status-badge {
            display: inline-block;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active { background: rgba(76,175,132,0.2); color: #4caf84; }
        .status-inactive { background: rgba(255,193,7,0.2); color: #ffc107; }
        .status-banned { background: rgba(232,116,90,0.2); color: #e8745a; }
        .role-badge {
            display: inline-block;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .role-admin { background: rgba(201,168,76,0.2); color: #c9a84c; }
        .role-user { background: rgba(100,149,237,0.2); color: #6495ed; }
        .action-btns {
            display: flex;
            gap: 0.3rem;
            flex-wrap: wrap;
        }
        .action-btns select, .action-btns button {
            padding: 0.2rem 0.5rem;
            border-radius: 0.4rem;
            border: 1px solid var(--border-light);
            background: var(--glass-bg);
            color: var(--text-secondary);
            font-size: 0.75rem;
            cursor: pointer;
        }
        .action-btns button {
            background: rgba(232,116,90,0.2);
            color: #e8745a;
            border-color: rgba(232,116,90,0.3);
        }
        .action-btns button:hover {
            background: rgba(232,116,90,0.3);
        }
        .message-box {
            padding: 0.8rem 1rem;
            border-radius: 0.8rem;
            margin-bottom: 1rem;
        }
        .message-success {
            background: rgba(76,175,132,0.1);
            border: 1px solid rgba(76,175,132,0.3);
            color: #4caf84;
        }
        .message-error {
            background: rgba(232,116,90,0.1);
            border: 1px solid rgba(232,116,90,0.3);
            color: #e8745a;
        }
        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .user-table { font-size: 0.8rem; }
            .user-table th, .user-table td { padding: 0.5rem; }
            .action-btns { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-top">
            <div class="brand-text">
                <h1>Mafuya <span class="highlight">Solution</span></h1>
                <div class="tagline">
                    <i class="fas fa-users-cog"></i> User Management
                </div>
            </div>
        </div>
        <div class="brand-bottom">
            <a href="index.php" style="color:var(--gold);text-decoration:none;font-weight:600;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="badge-date">
                <i class="fas fa-calendar-day"></i> 
                <span><?php echo date('Y-m-d H:i'); ?></span>
            </div>
        </div>
    </div>

    <div class="user-management">
        <h2><i class="fas fa-users"></i> All Users</h2>
        
        <?php if ($message): ?>
            <div class="message-box message-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="table-wrapper">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $user['status']; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <div class="action-btns">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="banned" <?php echo $user['status'] === 'banned' ? 'selected' : ''; ?>>Banned</option>
                                            </select>
                                            <input type="hidden" name="action" value="update_status">
                                        </div>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <div class="action-btns">
                                            <select name="role" onchange="this.form.submit()">
                                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                            <input type="hidden" name="action" value="update_role">
                                        </div>
                                    </form>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="delete_user">
                                        <button type="submit"><i class="fas fa-trash"></i></button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size:0.7rem;color:var(--text-muted);">(You)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Return to Dashboard</a>
    </div>
</div>
</body>
</html>