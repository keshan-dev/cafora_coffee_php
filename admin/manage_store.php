<?php
$requiredRole = "admin";
require '../includes/auth.php';
require '../includes/database_connection.php';

// Add Item
if (isset($_POST['add'])) {
    $store_id    = $_POST['store_id'];
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $status      = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO store_items (store_id,name,description,price,stock,status,created_at) VALUES (?,?,?,?,?,?,NOW())");
    $stmt->execute([$store_id,$name,$description,$price,$stock,$status]);
    header("Location: manage_store.php"); exit;
}

// Update Item
if (isset($_POST['update'])) {
    $id          = $_POST['item_id'];
    $store_id    = $_POST['store_id'];
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $status      = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE store_items SET store_id=?, name=?, description=?, price=?, stock=?, status=? WHERE id=?");
    $stmt->execute([$store_id,$name,$description,$price,$stock,$status,$id]);
    header("Location: manage_store.php"); exit;
}

// Delete Item
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM store_items WHERE id=?");
    $stmt->execute([$id]);
    header("Location: manage_store.php"); exit;
}

// Fetch Items & Stores
$items = $pdo->query("SELECT si.*, s.name AS store_name FROM store_items si JOIN stores s ON si.store_id=s.id ORDER BY si.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
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

        <!-- Button to Open Add Item Modal -->
        <button class="btn-add" onclick="openAddModal()">+ Add New Item</button>

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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= htmlspecialchars($item['store_name']) ?></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td>$<?= $item['price'] ?></td>
                            <td><?= $item['stock'] ?></td>
                            <td><?= $item['status'] ?></td>
                            <td>
                                <button class="btn-edit"
                                    onclick="openEditModal(
                                        '<?= $item['id'] ?>',
                                        '<?= $item['store_id'] ?>',
                                        '<?= htmlspecialchars($item['name'],ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($item['description'],ENT_QUOTES) ?>',
                                        '<?= $item['price'] ?>',
                                        '<?= $item['stock'] ?>',
                                        '<?= $item['status'] ?>'
                                    )">Edit</button>
                                <a class="btn-delete" href="manage_store.php?delete=<?= $item['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($items)): ?>
                        <tr><td colspan="8">No items found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Add New Item</h3>
        <form method="POST">
            <select name="store_id" required>
                <option value="">Select Store</option>
                <?php foreach($stores as $store): ?>
                    <option value="<?= $store['id'] ?>"><?= htmlspecialchars($store['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="name" placeholder="Item Name" required>
            <input type="text" name="description" placeholder="Description">
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock" placeholder="Stock" value="0" required>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" name="add" class="btn-add">Add Item</button>
        </form>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Item</h3>
        <form method="POST">
            <input type="hidden" name="item_id" id="edit_id">
            <select name="store_id" id="edit_store" required>
                <?php foreach($stores as $store): ?>
                    <option value="<?= $store['id'] ?>"><?= htmlspecialchars($store['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="name" id="edit_name" placeholder="Item Name" required>
            <input type="text" name="description" id="edit_description" placeholder="Description">
            <input type="number" name="price" id="edit_price" step="0.01" required>
            <input type="number" name="stock" id="edit_stock" required>
            <select name="status" id="edit_status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" name="update" class="btn-add">Save</button>
        </form>
    </div>
</div>

<script>
function openAddModal(){ document.getElementById('addModal').style.display='flex'; }
function closeAddModal(){ document.getElementById('addModal').style.display='none'; }

function openEditModal(id,store_id,name,desc,price,stock,status){
    document.getElementById('edit_id').value=id;
    document.getElementById('edit_store').value=store_id;
    document.getElementById('edit_name').value=name;
    document.getElementById('edit_description').value=desc;
    document.getElementById('edit_price').value=price;
    document.getElementById('edit_stock').value=stock;
    document.getElementById('edit_status').value=status;
    document.getElementById('editModal').style.display='flex';
}
function closeEditModal(){ document.getElementById('editModal').style.display='none'; }

window.onclick=function(e){
    if(e.target==document.getElementById('addModal')) closeAddModal();
    if(e.target==document.getElementById('editModal')) closeEditModal();
}

const toggle = document.querySelector('.toggle');
const container = document.querySelector('.container');
toggle.addEventListener('click',()=>{ container.classList.toggle('sidebar-open'); });
</script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
