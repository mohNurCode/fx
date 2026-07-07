<?php
require_once 'db.php';
require_once 'auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Get user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if (!$user) {
        header('Location: logout.php');
        exit;
    }
} catch(PDOException $e) {
    $message = 'Failed to load profile';
    $messageType = 'error';
}

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    try {
        $updates = [];
        $params = [];
        
        // Update full name
        if ($full_name !== $user['full_name']) {
            $updates[] = "full_name = ?";
            $params[] = $full_name;
        }
        
        // Update email
        if ($email !== $user['email']) {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user_id]);
            if ($stmt->fetch()) {
                $message = 'Email already in use by another account';
                $messageType = 'error';
            } else {
                $updates[] = "email = ?";
                $params[] = $email;
            }
        }
        
        // Update password
        if (!empty($current_password) && !empty($new_password)) {
            if (!verifyPassword($current_password, $user['password_hash'])) {
                $message = 'Current password is incorrect';
                $messageType = 'error';
            } elseif (strlen($new_password) < 6) {
                $message = 'New password must be at least 6 characters';
                $messageType = 'error';
            } elseif ($new_password !== $confirm_password) {
                $message = 'Passwords do not match';
                $messageType = 'error';
            } else {
                $updates[] = "password_hash = ?";
                $params[] = hashPassword($new_password);
            }
        }
        
        if (empty($message) && !empty($updates)) {
            $params[] = $user_id;
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $message = 'Profile updated successfully';
            $messageType = 'success';
            logActivity($pdo, $user_id, 'update_profile', 'User updated profile');
            
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            $_SESSION['user_name'] = $user['full_name'] ?? $user['username'];
        }
    } catch(PDOException $e) {
        $message = 'Update failed: ' . $e->getMessage();
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile · Mafuya Solution</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <style>
        .profile-container {
            padding: 1rem 0;
            max-width: 600px;
            margin: 0 auto;
        }
        .profile-container h2 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .profile-container h2 i {
            color: var(--gold);
        }
        .profile-form .form-group {
            margin-bottom: 1.2rem;
        }
        .profile-form label {
            display: block;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }
        .profile-form input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: var(--glass-bg);
            border: 1px solid var(--border-light);
            border-radius: 0.8rem;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .profile-form input:focus {
            outline: none;
            border-color: var(--gold);
        }
        .profile-form input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-save {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border: none;
            border-radius: 0.8rem;
            color: #0b1a26;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        .btn-save:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(201,168,76,0.3);
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
        .divider {
            border: none;
            border-top: 1px solid var(--border-light);
            margin: 1.5rem 0;
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
                    <i class="fas fa-user-circle"></i> My Profile
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

    <div class="profile-container">
        <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
        
        <?php if ($message): ?>
            <div class="message-box message-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="profile-form">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <hr class="divider">
            
            <h4 style="color:var(--text-secondary);margin-bottom:1rem;">Change Password</h4>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Current Password</label>
                <input type="password" name="current_password" placeholder="Enter current password">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password (min 6 characters)">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password">
            </div>
            
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Update Profile</button>
        </form>
        
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Return to Dashboard</a>
    </div>
</div>
</body>
</html>