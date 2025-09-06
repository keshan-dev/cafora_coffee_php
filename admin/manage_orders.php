<?php
$requiredRole = "admin";   // only admins can view this
require '../includes/auth.php';
require '../includes/database_connection.php';

// Update Order Status
if(isset($_POST['status'], $_POST['order_id'])){
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    header("Location: manage_orders.php");
    exit;
}

// Update Order Quantity (optional if you track quantities per order)
if(isset($_POST['update_quantity'])){
    $order_item_id = $_POST['order_item_id'];
    $quantity = $_POST['quantity'];
    $stmt = $pdo->prepare("UPDATE order_items SET quantity=? WHERE order_item_id=?");
    $stmt->execute([$quantity, $order_item_id]);
    header("Location: manage_orders.php");
    exit;
}

// Delete Order
if(isset($_GET['delete'])){
    $order_id = $_GET['delete'];
    // Prevent deleting completed orders
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE order_id=?");
    $stmt->execute([$order_id]);
    $status = $stmt->fetchColumn();
    if($status != 'completed'){
        $pdo->prepare("DELETE FROM order_items WHERE order_id=?")->execute([$order_id]);
        $pdo->prepare("DELETE FROM orders WHERE order_id=?")->execute([$order_id]);
    }
    header("Location: manage_orders.php");
    exit;
}

// Fetch Orders with items and totals
$orders = $pdo->query("
    SELECT o.order_id, o.user_id, o.total, o.status, o.created_at,
        GROUP_CONCAT(CONCAT(p.name, ' (x', oi.quantity, ')') SEPARATOR ', ') AS items
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN products p ON oi.item_id = p.id
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
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
                        <th>Total</th>
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
                        <td><?= $order['items'] ?></td>
                        <td>$<?= number_format($order['total'],2) ?></td>
                        <td>
                            <form method="POST" action="manage_orders.php">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <select name="status" onchange="this.form.submit()">
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
                                <span style="color:gray;">Completed</span>
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
