<?php
$requiredRole = "admin";   // only admins can view this
require '../includes/auth.php';
require '../includes/database_connection.php';

// ===== Update Order Status =====
if(isset($_POST['status']) && isset($_POST['order_id'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt_check = $pdo->prepare("SELECT status FROM orders WHERE order_id=?");
    $stmt_check->execute([$order_id]);
    $current = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if($current && $current['status'] != 'completed'){
        $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE order_id=?");
        $stmt->execute([$status, $order_id]);
    }

    header("Location: manage_orders.php");
    exit;
}

// ===== Delete Order =====
if(isset($_GET['delete'])){
    $order_id = $_GET['delete'];

    $stmt_check = $pdo->prepare("SELECT status FROM orders WHERE order_id=?");
    $stmt_check->execute([$order_id]);
    $order = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if($order && $order['status'] != 'completed'){
        $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id=?");
        $stmt->execute([$order_id]);

        // Also delete order items
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id=?");
        $stmt->execute([$order_id]);
    }

    header("Location: manage_orders.php");
    exit;
}

// ===== Fetch Orders with totals =====
$orders = $pdo->query("
    SELECT o.*, 
           SUM(oi.price * oi.quantity) AS total
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===== Fetch Order Items with Product Names =====
$order_items_map = [];
$stmt_items = $pdo->query("
    SELECT oi.*, p.name AS product_name 
    FROM order_items oi
    LEFT JOIN products p ON oi.item_id = p.id
");
foreach($stmt_items->fetchAll(PDO::FETCH_ASSOC) as $item){
    $order_items_map[$item['order_id']][] = $item;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="container">
    <?php include "nav.php"; ?>
    <div class="main">
        <div class="topbar">
            <div class="toggle">&#9776;</div>
            <h2>Cafora_Coffee Orders</h2>
        </div>

        <!-- Orders Table -->
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Items</th>
                        <th>Total ($)</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= $order['user_id'] ?></td>
                        <td>
                            <ul>
                                <?php 
                                if(isset($order_items_map[$order['order_id']])){
                                    foreach($order_items_map[$order['order_id']] as $item){
                                        echo "<li>{$item['product_name']} | Qty: {$item['quantity']} | Price: \${$item['price']}</li>";
                                    }
                                } else {
                                    echo "No items";
                                }
                                ?>
                            </ul>
                        </td>
                        <td><?= number_format($order['total'], 2) ?></td>
                        <td>
                            <form method="POST" action="manage_orders.php">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <select name="status" onchange="this.form.submit()" <?= $order['status']=='completed'?'':'' ?>>
                                    <?php
                                    $statuses = ['pending','processing','cancelled','completed'];
                                    foreach($statuses as $s){
                                        $sel = $order['status']==$s?'selected':'';
                                        echo "<option value='{$s}' {$sel}>{$s}</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                        </td>
                        <td><?= $order['created_at'] ?></td>
                        <td>
                            <?php if($order['status'] != 'completed'): ?>
                                <a class="btn-delete" href="manage_orders.php?delete=<?= $order['order_id'] ?>" onclick="return confirm('Delete this order?')">Delete</a>
                            <?php else: ?>
                                <span style="color:green;font-weight:bold;">Completed</span>
                            <?php endif; ?>
                        </td>
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
toggle.addEventListener('click',()=>{ container.classList.toggle('sidebar-open'); });
</script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
