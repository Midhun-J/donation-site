<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        if ($password === 'admin123') {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DonationHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="animated-bg"></div>
    
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2><a href="index.html" style="color: #4ecca3; text-decoration: none;">DonationHub</a></h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="login.php" class="nav-link login-btn">User Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-box admin-login">
            <h2 class="form-title">Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="admin.php">
                <div class="form-group">
                    <input type="text" name="username" class="form-input" placeholder="Username" value="admin" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-input" placeholder="Password" value="admin123" required>
                </div>
                <button type="submit" class="submit-btn">Login as Admin</button>
            </form>

            <div class="switch-text">
                
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>