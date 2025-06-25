<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Include database connection
require_once 'dbconnect.php';

// Initialize variables
$errors = [];
$formData = [
    'fullname' => '',
    'email' => '',
    'username' => '',
    'phone_number' => '',
    'address' => ''
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $formData['fullname'] = trim($_POST['fullname'] ?? '');
    $formData['email'] = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $formData['username'] = trim($_POST['username'] ?? '');
    $formData['phone_number'] = preg_replace('/[^0-9+]/', '', $_POST['phone_number'] ?? '');
    $formData['address'] = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate full name
    if (empty($formData['fullname'])) {
        $errors['fullname'] = 'Full name is required';
    } elseif (strlen($formData['fullname']) < 2 || strlen($formData['fullname']) > 50) {
        $errors['fullname'] = 'Full name must be between 2 and 50 characters';
    }
    
    // Validate email
    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    // Validate username
    if (empty($formData['username'])) {
        $errors['username'] = 'Username is required';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $formData['username'])) {
        $errors['username'] = 'Username must be 4-20 characters (letters, numbers, and _ only)';
    }
    
    // Validate phone (optional)
    if (!empty($formData['phone_number']) && !preg_match('/^[0-9+]{10,15}$/', $formData['phone_number'])) {
        $errors['phone_number'] = 'Please enter a valid phone number';
    }
    
    // Validate address
    if (empty($formData['address'])) {
        $errors['address'] = 'Address is required';
    } elseif (strlen($formData['address']) < 5 || strlen($formData['address']) > 200) {
        $errors['address'] = 'Address must be between 5 and 200 characters';
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    // Check for existing email or username
    if (empty($errors)) {
        $check_query = "SELECT user_id FROM user WHERE email = ? OR username = ? LIMIT 1";
        $stmt = $conn->prepare($check_query);
        
        if ($stmt) {
            $stmt->bind_param("ss", $formData['email'], $formData['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (strtolower($user['email']) === strtolower($formData['email'])) {
                    $errors['email'] = 'Email is already registered';
                } else {
                    $errors['username'] = 'Username is already taken';
                }
            }
            $stmt->close();
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(32));
        $verification_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $sql = "INSERT INTO user (
            fullname, 
            email, 
            username,
            password,
            phone_number,
            address
        ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param(
                "ssssis",
                $formData['fullname'],
                $formData['email'],
                $formData['username'],
                $hashed_password,
                $formData['phone_number'],
                $formData['address']
                
            );
            
            if ($stmt->execute()) {
                // Registration successful
                $_SESSION['registration_success'] = true;
                header('Location: login.php?registered=1');
                exit();
            } else {
                $errors['general'] = 'Registration failed. Please try again.';
            }
            $stmt->close();
        } else {
            $errors['general'] = 'Database error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create an account to shop for football kits and jerseys at Football Kits Nepal">
    <title>Sign Up - Football Kits Nepal</title>
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

        .register-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h2 {
            color: var(--secondary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.95rem;
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

        .form-control.is-invalid {
            border-color: var(--error-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e74c3c' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e74c3c' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: var(--error-color);
        }

        .is-invalid ~ .invalid-feedback {
            display: block;
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
            z-index: 10;
        }

        .btn-register {
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

        .btn-register:hover {
            background: #c1121f;
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: #4b5563;
        }

        .login-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.5rem;
            transition: var(--transition);
        }

        .login-link a:hover {
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

        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem;
            }
            
            .register-header h2 {
                font-size: 1.5rem;
            }
            
            .btn-register {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Create an Account</h2>
            <p>Join our football community today</p>
        </div>

        <?php if (!empty($errors['general'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($errors['general']); ?></span>
            </div>
        <?php endif; ?>

        <form id="registrationForm" action="" method="POST" novalidate>
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input 
                    type="text" 
                    id="fullname" 
                    name="fullname" 
                    class="form-control <?php echo isset($errors['fullname']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo htmlspecialchars($formData['fullname']); ?>"
                    required
                    autofocus
                >
                <?php if (isset($errors['fullname'])): ?>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['fullname']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo htmlspecialchars($formData['email']); ?>"
                    required
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['email']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo htmlspecialchars($formData['username']); ?>"
                    required
                    minlength="4"
                    maxlength="20"
                    pattern="[a-zA-Z0-9_]+"
                >
                <small style="display: block; margin-top: 0.25rem; color: #6c757d; font-size: 0.8rem;">
                    4-20 characters (letters, numbers, and _ only)
                </small>
                <?php if (isset($errors['username'])): ?>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['username']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input 
                    type="text" 
                    id="phone_number" 
                    name="phone_number" 
                    class="form-control <?php echo isset($errors['phone_number']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo htmlspecialchars($formData['phone_number']); ?>"
                    pattern="^[0-9+]{10,15}$"
                    required
                >
                <small style="display: block; margin-top: 0.25rem; color: #6c757d; font-size: 0.8rem;">
                    10-15 digits, can include +
                </small>
                <?php if (isset($errors['phone_number'])): ?>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['phone_number']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea
                    id="address"
                    name="address"
                    class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>"
                    required
                    minlength="5"
                    maxlength="200"
                    style="resize: vertical; min-height: 60px;"
                ><?php echo htmlspecialchars($formData['address']); ?></textarea>
                <?php if (isset($errors['address'])): ?>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['address']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                        required
                        minlength="8"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    >
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <small style="display: block; margin-top: 0.25rem; color: #6c757d; font-size: 0.8rem;">
                    At least 8 characters with uppercase, lowercase, and number
                </small>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['password']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                        required
                    >
                    <button type="button" class="password-toggle" id="toggleConfirmPassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <?php if (isset($errors['confirm_password'])): ?>
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errors['confirm_password']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Sign in</a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            button.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
        }

        // Initialize password toggles
        document.getElementById('togglePassword').addEventListener('click', function() {
            togglePassword('password', this);
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            togglePassword('confirm_password', this);
        });

        // Form validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
                
                const form = e.target;
                if (!document.querySelector('.error-message')) {
                    form.insertBefore(errorDiv, form.firstChild);
                }
                
                // Scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        // Focus on first invalid input on submit
        document.addEventListener('invalid', (function() {
            return function(e) {
                e.preventDefault();
                e.target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                e.target.focus();
            };
        })(), true);
    </script>
</body>
</html>
