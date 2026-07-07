<?php
require_once 'db.php';
require_once 'auth.php';

session_start();

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && verifyPassword($password, $user['password_hash'])) {
                if ($user['status'] !== 'active') {
                    $error = 'Your account is ' . $user['status'] . '. Please contact support.';
                } else {
                    // Login successful
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['full_name'] ?? $user['username'];
                    
                    // Update last login
                    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    // Log activity
                    logActivity($pdo, $user['id'], 'login', 'User logged in');
                    
                    header('Location: index.php');
                    exit;
                }
            } else {
                $error = 'Invalid username or password';
            }
        } catch(PDOException $e) {
            $error = 'Login failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login · Mafuya Solution</title>
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
        .login-container {
            background: rgba(11, 26, 38, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            padding: 3rem;
            max-width: 420px;
            width: 100%;
            border: 1px solid rgba(201, 168, 76, 0.1);
            box-shadow: 0 35px 80px -12px rgba(0,0,0,0.6);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            font-size: 2rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        .login-header h1 span {
            background: linear-gradient(135deg, #f0d080, #c9a84c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .login-header p {
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
        .btn-login {
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
        .btn-login:hover {
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
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
        }
        .login-footer a {
            color: #c9a84c;
            text-decoration: none;
            font-weight: 600;
        }
        .login-footer a:hover {
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
            .login-container { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Mafuya <span>Solution</span></h1>
            <p><span class="live-dot"></span> Secure Forex Platform</p>
        </div>
        
        <div class="error-message" id="errorMessage"><?php echo $error; ?></div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username or Email</label>
                <input type="text" name="username" placeholder="Enter your username or email" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Sign In</button>
        </form>
        
        <div class="login-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
        <div class="login-footer" style="margin-top:0.5rem;font-size:0.75rem;">
            Demo: admin / password
        </div>
    </div>
</body>
</html>