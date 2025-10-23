<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DonationHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="animated-bg"></div>
    
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>DonationHub</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="#profile" class="nav-link">Profile</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        <div class="donation-options">
           <div class="donation-card">
    <h3>Make New Donation</h3>
    <p>Support our causes</p>
    <a href="donate.php" class="donate-btn" style="display: inline-block; text-decoration: none; color: black; padding: 0.8rem 2rem;">Donate Now</a>
</div>
          <div class="donation-card">
    <h3>Donation History</h3>
    <p>View your contributions</p>
    <a href="donation_history.php" class="donate-btn" style="display: inline-block; text-decoration: none; color: black; padding: 0.8rem 2rem;">View History</a>
</div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>