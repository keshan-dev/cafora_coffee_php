<?php
$requiredRole = "admin"; // Only admins can access
require '../includes/auth.php';
require '../includes/database_connection.php';

// Handle Add / Update / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name    = $_POST['name'];
        $address = $_POST['address'];
        $phone   = $_POST['phone'];
        $status  = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO stores (name, address, phone, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $address, $phone, $status]);
    }

    if (isset($_POST['update'])) {
        $id      = $_POST['id'];
        $name    = $_POST['name'];
        $address = $_POST['address'];
        $phone   = $_POST['phone'];
        $status  = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE stores SET name=?, address=?, phone=?, status=? WHERE id=?");
        $stmt->execute([$name, $address, $phone, $status, $id]);
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM stores WHERE id=?");
        $stmt->execute([$id]);
    }
}

// Fetch all stores
$stores = $pdo->query("SELECT * FROM stores ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Stores - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- same style as manage_users -->
</head>
<body>
    <div class="container">
        <h1 class="page-title">Manage Stores</h1>

        <!-- Add Store Form -->
        <div class="card">
            <h2>Add New Store</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Store Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <select name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button type="submit" name="add">Add Store</button>
            </form>
        </div>

        <!-- Store List -->
        <div class="card">
            <h2>Store List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Store Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($stores as $store): ?>
                    <tr>
                        <td><?= $store['id'] ?></td>
                        <td><?= htmlspecialchars($store['name']) ?></td>
                        <td><?= htmlspecialchars($store['address']) ?></td>
                        <td><?= htmlspecialchars($store['phone']) ?></td>
                        <td><?= ucfirst($store['status']) ?></td>
                        <td><?= $store['created_at'] ?></td>
                        <td>
                            <!-- Update Form -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $store['id'] ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($store['name']) ?>" required>
                                <input type="text" name="address" value="<?= htmlspecialchars($store['address']) ?>" required>
                                <input type="text" name="phone" value="<?= htmlspecialchars($store['phone']) ?>" required>
                                <select name="status">
                                    <option value="active" <?= $store['status']=='active'?'selected':'' ?>>Active</option>
                                    <option value="inactive" <?= $store['status']=='inactive'?'selected':'' ?>>Inactive</option>
                                </select>
                                <button type="submit" name="update">Update</button>
                            </form>

                            <!-- Delete Form -->
                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this store?');">
                                <input type="hidden" name="id" value="<?= $store['id'] ?>">
                                <button type="submit" name="delete" class="danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($stores)): ?>
                    <tr><td colspan="7">No stores found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
