<?php
$requiredRole = "admin";
require '../includes/auth.php';
?>

<?php
require '../includes/database_connection.php';

// ===== User Stats =====
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$activeUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE status='active'")->fetchColumn();
$blockedUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE status='blocked'")->fetchColumn();

// ===== Orders Stats =====
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
$processingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='processing'")->fetchColumn();
$completedOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='completed'")->fetchColumn();
$cancelledOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='cancelled'")->fetchColumn();
$revenueToday = $pdo->query("
    SELECT SUM(oi.price*oi.quantity) 
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE DATE(o.created_at)=CURDATE()
")->fetchColumn();

// ===== Recent Users =====
$recentUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// ===== Recent Orders =====
$recentOrders = $pdo->query("
    SELECT o.order_id, u.name as customer_name, u.email as customer_email, o.status, o.created_at,
           SUM(oi.price*oi.quantity) as total_amount,
           GROUP_CONCAT(CONCAT(oi.item_id,' x',oi.quantity)) as items_ordered
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// ===== Charts Data =====
$roleData = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")->fetchAll(PDO::FETCH_ASSOC);
$roleLabels = []; $roleCounts = [];
foreach($roleData as $r){ $roleLabels[]=$r['role']; $roleCounts[]=$r['count']; }

$userGrowth = []; 
for($i=6;$i>=0;$i--){
    $date=date('Y-m-d',strtotime("-$i days"));
    $count=$pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at)='$date'")->fetchColumn();
    $userGrowth[]=['date'=>$date,'count'=>$count];
}
$growthLabels=array_column($userGrowth,'date');
$growthCounts=array_column($userGrowth,'count');

$orderGrowth = [];
for($i=6;$i>=0;$i--){
    $date=date('Y-m-d',strtotime("-$i days"));
    $count=$pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at)='$date'")->fetchColumn();
    $orderGrowth[]=['date'=>$date,'count'=>$count];
}
$orderLabels=array_column($orderGrowth,'date');
$orderCounts=array_column($orderGrowth,'count');

$topItems = $pdo->query("
    SELECT oi.item_id, SUM(oi.quantity) as total_sold
    FROM order_items oi
    GROUP BY oi.item_id
    ORDER BY total_sold DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <?php include "nav.php"; ?>
    <div class="main">
        <div class="topbar">
            <div class="toggle"><ion-icon name="menu-outline"></ion-icon></div>
            <h2>Cafora_Coffee</h2>
        </div>


        <!-- ORDERS STATS -->
        <h3>Orders Stats</h3>
        <div class="cards">
            <div class="card"><h3>Total Orders</h3><p><?= $totalOrders ?></p></div>
            <div class="card"><h3>Pending Orders</h3><p><?= $pendingOrders ?></p></div>
            <div class="card"><h3>Processing Orders</h3><p><?= $processingOrders ?></p></div>
            <div class="card"><h3>Completed Orders</h3><p><?= $completedOrders ?></p></div>
            <div class="card"><h3>Cancelled Orders</h3><p><?= $cancelledOrders ?></p></div>
            <div class="card"><h3>Revenue Today</h3><p>$<?= number_format($revenueToday,2) ?></p></div>
        </div>

        <!-- USER STATS -->
        <h3>Users Stats</h3>
        <div class="cards">
            <div class="card"><h3>Total Users</h3><p><?= $totalUsers ?></p></div>
            <div class="card"><h3>Total Admins</h3><p><?= $totalAdmins ?></p></div>
            <div class="card"><h3>Active Users</h3><p><?= $activeUsers ?></p></div>
            <div class="card"><h3>Blocked Users</h3><p><?= $blockedUsers ?></p></div>
        </div>

        

        <!-- CHARTS -->
        <div class="charts">
            <div class="chart-container">
                <h3>User Role Distribution</h3>
                <canvas id="roleChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>New Users Last 7 Days</h3>
                <canvas id="growthChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Orders Last 7 Days</h3>
                <canvas id="orderChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Top Selling Items</h3>
                <canvas id="topItemsChart"></canvas>
            </div>
        </div>

        <!-- RECENT TABLES -->
        <div class="recent-users">
            <h3>Recent Users</h3>
            <table class="styled-table">
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created At</th></tr>
                </thead>
                <tbody>
                    <?php foreach($recentUsers as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['status'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="recent-orders">
            <h3>Recent Orders</h3>
            <table class="styled-table">
                <thead>
                    <tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Total Amount</th><th>Status</th><th>Created At</th></tr>
                </thead>
                <tbody>
                    <?php foreach($recentOrders as $order): ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= $order['customer_name'] ?? $order['customer_email'] ?></td>
                        <td><?= $order['items_ordered'] ?></td>
                        <td>$<?= number_format($order['total_amount'],2) ?></td>
                        <td><?= $order['status'] ?></td>
                        <td><?= $order['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const toggle = document.querySelector('.toggle');
const container = document.querySelector('.container');
toggle.addEventListener('click', () => {
    container.classList.toggle('sidebar-open');
});
</script>

<script>
new Chart(document.getElementById('roleChart').getContext('2d'),{
    type:'pie',
    data:{
        labels: <?= json_encode($roleLabels) ?>,
        datasets:[{data: <?= json_encode($roleCounts) ?>, backgroundColor:['#4CAF50','#FFC107','#F44336','#2196F3']}]
    }
});
new Chart(document.getElementById('growthChart').getContext('2d'),{
    type:'line',
    data:{
        labels: <?= json_encode($growthLabels) ?>,
        datasets:[{label:'New Users', data: <?= json_encode($growthCounts) ?>, borderColor:'#4CAF50', backgroundColor:'rgba(76,175,80,0.2)', fill:true, tension:0.4}]
    }
});
new Chart(document.getElementById('orderChart').getContext('2d'),{
    type:'line',
    data:{
        labels: <?= json_encode($orderLabels) ?>,
        datasets:[{label:'Orders', data: <?= json_encode($orderCounts) ?>, borderColor:'#FF9800', backgroundColor:'rgba(255,152,0,0.2)', fill:true, tension:0.4}]
    }
});
new Chart(document.getElementById('topItemsChart').getContext('2d'),{
    type:'bar',
    data:{
        labels: <?= json_encode(array_column($topItems,'item_id')) ?>,
        datasets:[{label:'Quantity Sold', data: <?= json_encode(array_column($topItems,'total_sold')) ?>, backgroundColor:'#4CAF50'}]
    },
    options:{responsive:true, plugins:{legend:{display:false}}}
});
</script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
