 <?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect to the home page or dashboard
    header("Location: Home.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'dbconnect.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM User WHERE email = '$email'";
    if (isset($email) && !empty($email)) {
        //success
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "** Your email is Invalid **";
        }
        $sucess =  "** Congratulations ** ";
    } else {
        $error =  "** Please fill up the form! **";
    }
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
       
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['role']=="Admin") {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                header("Location: admin.php");
                exit();
                
            } else {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                header("Location: Home.php");
                exit();
            }
            
           

        } else {
            echo "Invalid password.";
        }
       
    } else {
        echo "No user found.";
    }
    

    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="form-footer">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        
        </div>
</body>
<script>
    document.querySelector("email")
</script>
</html>

