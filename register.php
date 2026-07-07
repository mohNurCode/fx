<?php
require_once 'db.php';
require_once 'auth.php';

session_start();

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        try {
            // Check if username or email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'Username or email already exists';
            } else {
                // Create user
                $password_hash = hashPassword($password);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password_hash, full_name, role, status) 
                    VALUES (?, ?, ?, ?, 'user', 'active')
                ");
                $stmt->execute([$username, $email, $password_hash, $full_name]);
                
                $user_id = $pdo->lastInsertId();
                logActivity($pdo, $user_id, 'register', 'New user registered');
                
                $success = 'Registration successful! You can now login.';
                $_POST = []; // Clear form
            }
        } catch(PDOException $e) {
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register · Mafuya Solution</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #0b1a26, #132d3f, #0b1a26);
            padding: 1rem;
        }
        .register-container {
            background: rgba(11, 26, 38, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            padding: 3rem;
            max-width: 460px;
            width: 100%;
            border: 1px solid rgba(201, 168, 76, 0.1);
            box-shadow: 0 35px 80px -12px rgba(0,0,0,0.6);
        }
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-header h1 {
            font-size: 2rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        .register-header h1 span {
            background: linear-gradient(135deg, #f0d080, #c9a84c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .register-header p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.8rem;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #c9a84c;
            background: rgba(201, 168, 76, 0.05);
        }
        .form-group input::placeholder {
            color: rgba(255,255,255,0.3);
        }
        .btn-register {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #c9a84c, #f0d080);
            border: none;
            border-radius: 0.8rem;
            color: #0b1a26;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        .btn-register:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(201, 168, 76, 0.3);
        }
        .error-message {
            background: rgba(232, 116, 90, 0.1);
            border: 1px solid rgba(232, 116, 90, 0.3);
            color: #e8745a;
            padding: 0.8rem;
            border-radius: 0.8rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: <?php echo $error ? 'block' : 'none'; ?>;
        }
        .success-message {
            background: rgba(76, 175, 132, 0.1);
            border: 1px solid rgba(76, 175, 132, 0.3);
            color: #4caf84;
            padding: 0.8rem;
            border-radius: 0.8rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: <?php echo $success ? 'block' : 'none'; ?>;
        }
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
        }
        .register-footer a {
            color: #c9a84c;
            text-decoration: none;
            font-weight: 600;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
        .live-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            background: #4caf84;
            border-radius: 50%;
            animation: livePulse 1.5s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(0.7); }
        }
        @media (max-width: 500px) {
            .register-container { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Mafuya <span>Solution</span></h1>
            <p><span class="live-dot"></span> Create your account</p>
        </div>
        
        <div class="error-message" id="errorMessage"><?php echo $error; ?></div>
        <div class="success-message" id="successMessage"><?php echo $success; ?></div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username *</label>
                <input type="text" name="username" placeholder="Choose a username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email *</label>
                <input type="email" name="email" placeholder="Enter your email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password *</label>
                <input type="password" name="password" placeholder="Min 6 characters" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm Password *</label>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn-register"><i class="fas fa-user-plus"></i> Create Account</button>
        </form>
        
        <div class="register-footer">
            Already have an account? <a href="login.php">Sign in here</a>
        </div>
    </div>
</body>
</html>