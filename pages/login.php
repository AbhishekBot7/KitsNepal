<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirect = ($_SESSION['is_admin'] == 1) ? 'admin/dashboard.php' : 'index.php';
    header("Location: $redirect");
    exit();
}

// Initialize variables
$error = '';
$email = '';

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'dbconnect.php';

    // Sanitize and validate input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // Validate input
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($password)) {
        $error = 'Please enter your password.';
    } else {
        // Prepare and execute query
        $sql = "SELECT user_id, email, password, is_admin, fullname FROM user WHERE email = ? AND is_active = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    
                    // Set remember me cookie if requested
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + (30 * 24 * 60 * 60); // 30 days
                        setcookie('remember_token', $token, $expires, '/');
                        
                        // Store token in database (you'll need to add this field to your users table)
                        $stmt = $conn->prepare("UPDATE user SET remember_token = ?, token_expires = ? WHERE user_id = ?");
                        $expires_date = date('Y-m-d H:i:s', $expires);
                        $stmt->bind_param("ssi", $token, $expires_date, $user['user_id']);
                        $stmt->execute();
                    }
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Redirect based on user role
                    $redirect = ($user['is_admin'] == 1) ? 'admin/dashboard.php' : 'index.php';
                    header("Location: $redirect");
                    exit();
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'No account found with this email or account is inactive.';
            }
            $stmt->close();
        } else {
            $error = 'Database error. Please try again later.';
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login to your Football Kits Nepal account to access your orders and preferences.">
    <title>Login - Football Kits Nepal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e63946;
            --secondary-color: #1d3557;
            --accent-color: #a8dadc;
            --text-dark: #1d3557;
            --text-light: #f1faee;
            --background: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--secondary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }

        .password-input {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.25rem;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-color);
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            background: #c1121f;
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            color: var(--text-dark);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-social:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .btn-social i {
            font-size: 1.2rem;
        }

        .btn-google {
            color: #db4437;
        }

        .btn-facebook {
            color: #4267B2;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: #4b5563;
        }

        .register-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.5rem;
            transition: var(--transition);
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: #fee2e2;
            color: var(--error-color);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 4px solid var(--error-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-message i {
            font-size: 1.2rem;
        }

        .success-message {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 4px solid #16a34a;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success-message i {
            font-size: 1.2rem;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
            
            .btn-login, .btn-social {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Login to access your account</p>
        </div>

        <?php if (isset($_GET['registered']) && $_GET['registered'] === 'true'): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>Registration successful! Please login to continue.</span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>Password reset successful! Please login with your new password.</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="on">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Enter your email" 
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Enter your password" 
                        required
                    >
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" style="margin-top: 1rem;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
        });

        // Focus on first input field
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('input:not([type="hidden"])');
            if (firstInput) firstInput.focus();
        });
    </script>
</body>
</html>

