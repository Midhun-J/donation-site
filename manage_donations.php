<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}
include 'config.php';

// Get all donations with user info
$donations_sql = "SELECT d.*, u.name as user_name, u.email as user_email 
                  FROM donations d 
                  LEFT JOIN users u ON d.user_id = u.id 
                  ORDER BY d.donation_date DESC";
$donations_result = mysqli_query($conn, $donations_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donations - DonationHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            margin-top: 100px;
            padding: 2rem;
        }
        
        .donations-table {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 2rem;
        }
        
        .donations-table th,
        .donations-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .donations-table th {
            background: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }
        
        .status-completed {
            color: #4ecca3;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffa726;
            font-weight: bold;
        }
        
        .back-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #4ecca3;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-weight: bold;
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
                <li class="nav-item"><a href="admin_dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link admin-btn">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="admin-container">
        <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
        <h1>Manage Donations</h1>

        <div class="donations-table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Cause</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($donations_result) > 0): ?>
                        <?php while($donation = mysqli_fetch_assoc($donations_result)): ?>
                            <tr>
                                <td><?php echo $donation['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($donation['user_name']); ?><br>
                                    <small><?php echo htmlspecialchars($donation['user_email']); ?></small>
                                </td>
                                <td>$<?php echo number_format($donation['amount'], 2); ?></td>
                                <td><?php echo ucfirst($donation['cause']); ?></td>
                                <td class="status-<?php echo $donation['status']; ?>">
                                    <?php echo ucfirst($donation['status']); ?>
                                </td>
                                <td><?php echo date('M j, Y g:i A', strtotime($donation['donation_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No donations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>