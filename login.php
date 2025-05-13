<?php 
include 'config.php'; 
session_start(); 

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Hospital Management System</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="png" href="https://cdn-icons-png.flaticon.com/512/5432/5432747.png">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <span class="logo-icon">🏥</span>
            </div>
            <h1>Hospital Management</h1>
            <p>Sign in to access your dashboard</p>
        </div>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="login-form-container">
            <form action="login_process.php" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <span class="input-icon">👤</span>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">Sign In</button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Create Account</a></p>
            </div>
        </div>
    </div>
    
    <style>
        /* Login specific styles */
        .login-page {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }
        
        .login-header {
            text-align: center;
            padding: 30px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .logo-icon {
            font-size: 2.5rem;
            color: white;
        }
        
        .login-header h1 {
            margin: 0;
            font-size: 1.8rem;
            color: var(--text-dark);
            border-bottom: none;
            padding: 0;
        }
        
        .login-header p {
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .login-form-container {
            padding: 30px;
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
        }
        
        .login-form label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }
        
        .login-form input {
            padding-left: 45px;
            font-size: 1rem;
        }
        
        .login-btn {
            background: var(--primary);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 25px;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</body>
</html>