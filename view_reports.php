<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}
include 'config.php';

// Get donation statistics
$total_donations = mysqli_query($conn, "SELECT COUNT(*) as total FROM donations");
$total_donations_count = mysqli_fetch_assoc($total_donations)['total'];

$total_amount = mysqli_query($conn, "SELECT SUM(amount) as total FROM donations WHERE status='completed'");
$total_amount_sum = mysqli_fetch_assoc($total_amount)['total'] ?? 0;

$avg_donation = mysqli_query($conn, "SELECT AVG(amount) as average FROM donations WHERE status='completed'");
$avg_donation_amount = mysqli_fetch_assoc($avg_donation)['average'] ?? 0;

// Get donations by cause
$cause_stats = mysqli_query($conn, "
    SELECT cause, COUNT(*) as count, SUM(amount) as total 
    FROM donations 
    WHERE status='completed' 
    GROUP BY cause
");

// Monthly donations
$monthly_stats = mysqli_query($conn, "
    SELECT 
        DATE_FORMAT(donation_date, '%Y-%m') as month,
        COUNT(*) as count,
        SUM(amount) as total
    FROM donations 
    WHERE status='completed'
    GROUP BY DATE_FORMAT(donation_date, '%Y-%m')
    ORDER BY month DESC
    LIMIT 6
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports - DonationHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            margin-top: 100px;
            padding: 2rem;
        }
        
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
        
        .report-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
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
        
        .data-table {
            width: 100%;
            margin-top: 1rem;
        }
        
        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .data-table th {
            background: rgba(255, 255, 255, 0.2);
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
        <h1>Donation Reports & Analytics</h1>

        <!-- Key Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_donations_count; ?></div>
                <div class="stat-label">Total Donations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$<?php echo number_format($total_amount_sum, 2); ?></div>
                <div class="stat-label">Total Amount Raised</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$<?php echo number_format($avg_donation_amount, 2); ?></div>
                <div class="stat-label">Average Donation</div>
            </div>
        </div>

        <!-- Donations by Cause -->
        <div class="report-section">
            <h3>Donations by Cause</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cause</th>
                        <th>Number of Donations</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($cause_stats) > 0): ?>
                        <?php while($cause = mysqli_fetch_assoc($cause_stats)): ?>
                            <tr>
                                <td><?php echo ucfirst($cause['cause']); ?></td>
                                <td><?php echo $cause['count']; ?></td>
                                <td>$<?php echo number_format($cause['total'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No donation data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Monthly Statistics -->
        <div class="report-section">
            <h3>Monthly Donations (Last 6 Months)</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Number of Donations</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($monthly_stats) > 0): ?>
                        <?php while($month = mysqli_fetch_assoc($monthly_stats)): ?>
                            <tr>
                                <td><?php echo date('F Y', strtotime($month['month'] . '-01')); ?></td>
                                <td><?php echo $month['count']; ?></td>
                                <td>$<?php echo number_format($month['total'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No monthly data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>