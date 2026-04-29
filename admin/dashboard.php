<?php
require_once '../config.php';
requireRole('admin');

$user_id = $_SESSION['user_id'];

// Get stats
$stats = [];
$result = $conn->query("SELECT COUNT(*) as total_users FROM users WHERE role != 'admin'");
$stats['total_users'] = $result->fetch_assoc()['total_users'];

$result = $conn->query("SELECT COUNT(*) as total_students FROM students");
$stats['total_students'] = $result->fetch_assoc()['total_students'];

$result = $conn->query("SELECT COUNT(*) as active_trips FROM trips WHERE status IN ('picked_up', 'in_transit')");
$stats['active_trips'] = $result->fetch_assoc()['active_trips'];

$result = $conn->query("SELECT COUNT(*) as pending_drivers FROM drivers WHERE status = 'pending'");
$stats['pending_drivers'] = $result->fetch_assoc()['pending_drivers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - School Transport System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="nav">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_drivers.php">Manage Drivers</a></li>
                <li><a href="manage_students.php">Manage Students</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../generate_payments.php">Generate Payments</a></li>
                <li><a href="../insert_sample_data.php">Insert Sample Data</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>

        <h1>Admin Dashboard</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $stats['total_users']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Students</h3>
                <p><?php echo $stats['total_students']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Active Trips</h3>
                <p><?php echo $stats['active_trips']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Drivers</h3>
                <p><?php echo $stats['pending_drivers']; ?></p>
            </div>
        </div>

        <h2>Recent Activities</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT message, created_at FROM notifications ORDER BY created_at DESC LIMIT 10");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['message']}</td><td>{$row['created_at']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <style>
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #555;
        }
        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
    </style>
</body>
</html>