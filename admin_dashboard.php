<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}
include 'config.php';

// Get stats
$users_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$users_total = mysqli_fetch_assoc($users_count)['total'];

$donations_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM donations");
$donations_total = mysqli_fetch_assoc($donations_count)['total'];

$total_amount = mysqli_query($conn, "SELECT SUM(amount) as total FROM donations WHERE status='completed'");
$amount_total = mysqli_fetch_assoc($total_amount)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DonationHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4ecca3;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #ccc;
            font-size: 1.1rem;
        }
        
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .admin-btn {
            padding: 1rem;
            background: #4ecca3;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .admin-btn:hover {
            background: #3da789;
            transform: translateY(-2px);
        }
        
        .admin-btn.danger {
            background: #ff6b6b;
        }
        
        .admin-btn.danger:hover {
            background: #e55a5a;
        }
        
        .recent-donations {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .donation-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .donation-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>DonationHub Admin</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link admin-btn">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <h1>Welcome, Admin <?php echo $_SESSION['admin_username']; ?>!</h1>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $users_total; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $donations_total; ?></div>
                <div class="stat-label">Total Donations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$<?php echo number_format($amount_total, 2); ?></div>
                <div class="stat-label">Total Amount</div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="admin-actions">
            <a href="manage_users.php" class="admin-btn">Manage Users</a>
            <a href="manage_donations.php" class="admin-btn">Manage Donations</a>
            <a href="view_reports.php" class="admin-btn">View Reports</a>
            <a href="logout.php" class="admin-btn danger">Logout</a>
        </div>

        <!-- Recent Donations -->
        <div class="recent-donations">
            <h3>Recent Donations</h3>
            <?php
            $recent_sql = "SELECT d.*, u.name as user_name 
                          FROM donations d 
                          LEFT JOIN users u ON d.user_id = u.id 
                          ORDER BY d.donation_date DESC 
                          LIMIT 5";
            $recent_result = mysqli_query($conn, $recent_sql);
            
            if (mysqli_num_rows($recent_result) > 0) {
                while($donation = mysqli_fetch_assoc($recent_result)) {
                    echo '<div class="donation-item">';
                    echo '<div>';
                    echo '<strong>' . $donation['user_name'] . '</strong>';
                    echo '<br><small>Cause: ' . ucfirst($donation['cause']) . '</small>';
                    echo '</div>';
                    echo '<div style="text-align: right;">';
                    echo '<strong>$' . number_format($donation['amount'], 2) . '</strong>';
                    echo '<br><small>' . date('M j, Y', strtotime($donation['donation_date'])) . '</small>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No donations yet.</p>';
            }
            ?>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>