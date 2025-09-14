<?php
$requiredRole = "admin";
require '../includes/auth.php';
require '../includes/database_connection.php';

// Update Issued only
if (isset($_POST['update_issued'])) {
    $id     = $_POST['item_id'];
    $issued = (int) $_POST['issued'];

    // make sure issued is not negative
    if ($issued >= 0) {
        $stmt = $pdo->prepare("UPDATE store_items SET issued=? WHERE id=?");
        $stmt->execute([$issued, $id]);
    }
    header("Location: manage_store.php");
    exit;
}

// Delete Item
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM store_items WHERE id=?");
    $stmt->execute([$id]);
    header("Location: manage_store.php"); exit;
}

// Fetch Items & Stores
$items = $pdo->query("SELECT si.*, s.name AS store_name 
                      FROM store_items si 
                      JOIN stores s ON si.store_id=s.id 
                      ORDER BY si.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$stores = $pdo->query("SELECT * FROM stores WHERE status='active' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Store Items</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="container">
    <?php include "nav.php"; ?>
    <div class="main">
        <div class="topbar">
            <div class="toggle">&#9776;</div>
            <h2>Cafora_Coffee</h2>
        </div>

        <!-- Items Table -->
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Store</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Issued</th>
                        <th>Available</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                        <?php 
                            $issued    = $item['issued'] ?? 0; 
                            $available = $item['stock'] - $issued; 
                        ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= htmlspecialchars($item['store_name']) ?></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td>$<?= $item['price'] ?></td>
                            <td><?= $item['stock'] ?></td>
                            <td><?= $issued ?></td>
                            <td><?= $available ?></td>
                            <td><?= $item['status'] ?></td>
                            <td>
                                <button class="btn-edit"
                                    onclick="openIssuedModal(
                                        '<?= $item['id'] ?>',
                                        '<?= htmlspecialchars($item['store_name'],ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($item['name'],ENT_QUOTES) ?>',
                                        '<?= $item['price'] ?>',
                                        '<?= $item['stock'] ?>',
                                        '<?= $issued ?>'
                                    )">Edit</button>
                                <a class="btn-delete" href="manage_store.php?delete=<?= $item['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($items)): ?>
                        <tr><td colspan="10">No items found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Issued Edit Modal -->
<div id="issuedModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeIssuedModal()">&times;</span>
        <h3>Edit Issued Count</h3>
        <form method="POST">
            <input type="hidden" name="item_id" id="issued_id">

            <label>Store</label>
            <input type="text" id="issued_store" readonly>

            <label>Name</label>
            <input type="text" id="issued_name" readonly>

            <label>Price</label>
            <input type="text" id="issued_price" readonly>

            <label>Stock</label>
            <input type="text" id="issued_stock" readonly>

            <label>Issued</label>
            <input type="number" name="issued" id="issued_value" min="0" required>

            <button type="submit" name="update_issued" class="btn-add">Save</button>
        </form>
    </div>
</div>

<script>
function openIssuedModal(id,store,name,price,stock,issued){
    document.getElementById('issued_id').value=id;
    document.getElementById('issued_store').value=store;
    document.getElementById('issued_name').value=name;
    document.getElementById('issued_price').value=price;
    document.getElementById('issued_stock').value=stock;
    document.getElementById('issued_value').value=issued;
    document.getElementById('issuedModal').style.display='flex';
}
function closeIssuedModal(){ document.getElementById('issuedModal').style.display='none'; }

window.onclick=function(e){
    if(e.target==document.getElementById('issuedModal')) closeIssuedModal();
}

const toggle = document.querySelector('.toggle');
const container = document.querySelector('.container');
toggle.addEventListener('click',()=>{ container.classList.toggle('sidebar-open'); });
</script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
