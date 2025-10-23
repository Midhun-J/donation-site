<?php
session_start();
include 'config.php';

$error = '';
$success = '';
$active_tab = 'login';

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['login_email']);
    $password = mysqli_real_escape_string($conn, $_POST['login_password']);
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    $active_tab = 'login';
}

// Handle signup
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup_name'])) {
    $name = mysqli_real_escape_string($conn, $_POST['signup_name']);
    $email = mysqli_real_escape_string($conn, $_POST['signup_email']);
    $password = mysqli_real_escape_string($conn, $_POST['signup_password']);
    
    $check_sql = "SELECT id FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Email already registered!";
    } else {
        $insert_sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        
        if (mysqli_query($conn, $insert_sql)) {
            $success = "Registration successful! Please login.";
            $active_tab = 'login';
        } else {
            $error = "Registration failed: " . mysqli_error($conn);
        }
    }
    $active_tab = 'signup';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - DonationHub</title>
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
                <li class="nav-item"><a href="admin.php" class="nav-link admin-btn">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-box">
            <div class="login-tabs">
                <button class="login-tab <?php echo $active_tab == 'login' ? 'active' : ''; ?>" onclick="switchTab('login')">Login</button>
                <button class="login-tab <?php echo $active_tab == 'signup' ? 'active' : ''; ?>" onclick="switchTab('signup')">Sign Up</button>
            </div>

            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="login.php" class="login-form <?php echo $active_tab == 'login' ? 'active' : ''; ?>" id="loginForm">
                <h2 class="form-title">Welcome Back</h2>
                <div class="form-group">
                    <input type="email" name="login_email" class="form-input" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="login_password" class="form-input" placeholder="Password" required>
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>

            <!-- Signup Form -->
            <form method="POST" action="login.php" class="login-form <?php echo $active_tab == 'signup' ? 'active' : ''; ?>" id="signupForm">
                <h2 class="form-title">Create Account</h2>
                <div class="form-group">
                    <input type="text" name="signup_name" class="form-input" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="signup_email" class="form-input" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="signup_password" class="form-input" placeholder="Password" required>
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Update tabs
            document.querySelectorAll('.login-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`.login-tab:nth-child(${tabName === 'login' ? 1 : 2})`).classList.add('active');
            
            // Update forms
            document.querySelectorAll('.login-form').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById(tabName + 'Form').classList.add('active');
        }
    </script>
    <script src="script.js"></script>
</body>
</html>