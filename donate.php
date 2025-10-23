<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = floatval($_POST['amount']);
    $cause = mysqli_real_escape_string($conn, $_POST['cause']);
    
    if ($amount > 0) {
        $sql = "INSERT INTO donations (user_id, amount, cause, status) 
                VALUES ('$user_id', '$amount', '$cause', 'completed')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Thank you for your donation of $<strong>" . number_format($amount, 2) . "</strong> to <strong>" . ucfirst($cause) . "</strong> cause!";
        } else {
            $error = "Donation failed: " . mysqli_error($conn);
        }
    } else {
        $error = "Please enter a valid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Donation - DonationHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .donation-container {
            margin-top: 100px;
            padding: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .donation-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .cause-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .cause-option {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .cause-option:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .cause-option.selected {
            border-color: #4ecca3;
            background: rgba(78, 204, 163, 0.2);
        }
        
        .amount-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .amount-option {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.8rem;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .amount-option:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .amount-option.selected {
            border-color: #4ecca3;
            background: rgba(78, 204, 163, 0.2);
        }
        
        .custom-amount {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: #f0f0f0;
            font-size: 16px;
            margin-top: 0.5rem;
        }
        
        .donate-submit-btn {
            width: 100%;
            padding: 15px;
            background: #4ecca3;
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .donate-submit-btn:hover {
            background: #3da789;
            transform: translateY(-2px);
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
                <h2>DonationHub</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="donation-container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        
        <div class="donation-card">
            <h1 style="text-align: center; margin-bottom: 2rem; color: #4ecca3;">Make a Donation</h1>
            <p style="text-align: center; margin-bottom: 2rem; color: #ccc;">Welcome, <?php echo $_SESSION['user_name']; ?>! Your contribution makes a difference.</p>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
                    <a href="donate.php" class="back-btn" style="background: #ff6b6b;">Make Another Donation</a>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="message error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="donate.php">
                    <!-- Select Cause -->
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 1rem; font-weight: bold;">Select Cause:</label>
                        <div class="cause-options">
                            <div class="cause-option selected" data-cause="food">
                                🍽️ Food Support
                            </div>
                            <div class="cause-option" data-cause="education">
                                📚 Education
                            </div>
                            <div class="cause-option" data-cause="medical">
                                🏥 Medical Aid
                            </div>
                            <div class="cause-option" data-cause="shelter">
                                🏠 Shelter
                            </div>
                        </div>
                        <input type="hidden" name="cause" id="selectedCause" value="food" required>
                    </div>

                    <!-- Select Amount -->
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 1rem; font-weight: bold;">Donation Amount:</label>
                        <div class="amount-options">
                            <div class="amount-option" data-amount="10">$10</div>
                            <div class="amount-option" data-amount="25">$25</div>
                            <div class="amount-option" data-amount="50">$50</div>
                            <div class="amount-option" data-amount="100">$100</div>
                        </div>
                        <input type="number" name="amount" id="customAmount" class="custom-amount" placeholder="Or enter custom amount" min="1" step="0.01" required>
                    </div>

                    <button type="submit" class="donate-submit-btn">Donate Now</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Cause selection
        document.querySelectorAll('.cause-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.cause-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                document.getElementById('selectedCause').value = this.getAttribute('data-cause');
            });
        });

        // Amount selection
        document.querySelectorAll('.amount-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.amount-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                document.getElementById('customAmount').value = this.getAttribute('data-amount');
            });
        });

        // Clear preset amount when typing custom amount
        document.getElementById('customAmount').addEventListener('input', function() {
            document.querySelectorAll('.amount-option').forEach(opt => {
                opt.classList.remove('selected');
            });
        });
    </script>
    <script src="script.js"></script>
</body>
</html>