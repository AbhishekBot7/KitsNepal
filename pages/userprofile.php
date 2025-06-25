<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "footballkitsnepal");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
    $address = $_POST['address'];

    $sql = "UPDATE user SET fullname=?, email=?, phone_number=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $fullname, $email, $phone_number, $address, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Football Kits Nepal</title>
    <link rel="stylesheet" href="../css/Home.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #1d3557;
        }
        .profile-container {
            max-width: 480px;
            margin: 48px auto 32px auto;
            padding: 2.5rem 2rem 2rem 2rem;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(30,41,59,0.10);
            position: relative;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e63946 0%, #a8dadc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            box-shadow: 0 2px 8px rgba(230,57,70,0.10);
        }
        .profile-avatar i {
            color: #fff;
            font-size: 2.5rem;
        }
        .profile-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #1d3557;
        }
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .form-group label {
            font-weight: 600;
            color: #1d3557;
            font-size: 1rem;
        }
        .form-group input,
        .form-group textarea {
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            background: #f8fafc;
            transition: border 0.2s;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #e63946;
            outline: none;
        }
        .form-group textarea {
            height: 90px;
            resize: vertical;
        }
        .submit-btn {
            background: linear-gradient(135deg, #e63946 0%, #a8dadc 100%);
            color: #fff;
            padding: 14px 0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(230,57,70,0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #c1121f 0%, #a8dadc 100%);
            box-shadow: 0 4px 16px rgba(230,57,70,0.15);
        }
        .message {
            padding: 12px;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            text-align: center;
            font-size: 1rem;
        }
        .success {
            background: #d4edda;
            color: #166534;
            border: 1px solid #b7e4c7;
        }
        .error {
            background: #f8d7da;
            color: #b91c1c;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 600px) {
            .profile-container {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
        }
    </style>
</head>
<body>
<?php include '../components/nav.php'; ?>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2>My Profile</h2>
            <?php if (isset($success_message)): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>

        <form class="profile-form" method="POST" action="">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required pattern="^[0-9+]{10,15}$" title="Enter digits only, 10-15 digits, can include +">
                <small style="color:#888; font-size:0.9em;">10-15 digits, can include +</small>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

            <button type="submit" class="submit-btn">Update Profile</button>
        </form>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Football Kits Nepal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 