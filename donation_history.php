<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Get user's donation history
$user_id = $_SESSION['user_id'];
$donations_sql = "SELECT * FROM donations WHERE user_id = '$user_id' ORDER BY donation_date DESC";
$donations_result = mysqli_query($conn, $donations_sql);

// Calculate total donations
$total_sql = "SELECT SUM(amount) as total FROM donations WHERE user_id = '$user_id' AND status = 'completed'";
$total_result = mysqli_query($conn, $total_sql);
$total_data = mysqli_fetch_assoc($total_result);
$total_donated = $total_data['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History - DonationHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .history-container {
            margin-top: 100px;
            padding: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .history-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .total-amount {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4ecca3;
            margin-bottom: 0.5rem;
        }
        
        .total-label {
            color: #ccc;
            font-size: 1.1rem;
        }
        
        .donations-table {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 1rem;
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
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #7e7e7e;
        }
        
        .empty-state .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .cause-badge {
            padding: 0.3rem 0.8rem;
            background: rgba(78, 204, 163, 0.2);
            border-radius: 20px;
            font-size: 0.9rem;
            border: 1px solid #4ecca3;
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>DonationHub</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="donate.php" class="nav-link">Make Donation</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="history-container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        
        <div class="history-card">
            <h1 style="text-align: center; margin-bottom: 2rem; color: #4ecca3;">Your Donation History</h1>
            <p style="text-align: center; margin-bottom: 2rem; color: #ccc;">Hello, <?php echo $_SESSION['user_name']; ?>! Here's your contribution history.</p>

            <!-- Total Donations Stats -->
            <div class="stats-card">
                <div class="total-amount">$<?php echo number_format($total_donated, 2); ?></div>
                <div class="total-label">Total Donated</div>
            </div>

            <!-- Donations Table -->
            <?php if (mysqli_num_rows($donations_result) > 0): ?>
                <div class="donations-table">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Cause</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($donation = mysqli_fetch_assoc($donations_result)): ?>
                                <tr>
                                    <td>
                                        <?php echo date('M j, Y', strtotime($donation['donation_date'])); ?><br>
                                        <small style="color: #7e7e7e;">
                                            <?php echo date('g:i A', strtotime($donation['donation_date'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="cause-badge">
                                            <?php 
                                            $cause_icons = [
                                                'food' => '🍽️',
                                                'education' => '📚', 
                                                'medical' => '🏥',
                                                'shelter' => '🏠'
                                            ];
                                            echo ($cause_icons[$donation['cause']] ?? '❤️') . ' ' . ucfirst($donation['cause']);
                                            ?>
                                        </span>
                                    </td>
                                    <td style="font-weight: bold; font-size: 1.1rem;">
                                        $<?php echo number_format($donation['amount'], 2); ?>
                                    </td>
                                    <td class="status-<?php echo $donation['status']; ?>">
                                        <?php echo ucfirst($donation['status']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">📊</div>
                    <h3>No Donations Yet</h3>
                    <p>You haven't made any donations yet. Start making a difference today!</p>
                    <a href="donate.php" class="back-btn" style="margin-top: 1rem;">Make Your First Donation</a>
                </div>
            <?php endif; ?>

            <!-- Quick Stats -->
            <?php if (mysqli_num_rows($donations_result) > 0): ?>
                <?php
                // Count donations by cause
                mysqli_data_seek($donations_result, 0);
                $cause_count = [];
                while($donation = mysqli_fetch_assoc($donations_result)) {
                    $cause = $donation['cause'];
                    if (!isset($cause_count[$cause])) {
                        $cause_count[$cause] = 0;
                    }
                    $cause_count[$cause]++;
                }
                ?>
                <div style="margin-top: 2rem; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 8px;">
                    <h4 style="margin-bottom: 1rem; color: #4ecca3;">Your Giving Summary</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                        <?php foreach($cause_count as $cause => $count): ?>
                            <div style="text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: #4ecca3;"><?php echo $count; ?></div>
                                <div style="color: #ccc; font-size: 0.9rem;"><?php echo ucfirst($cause); ?> Donations</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>