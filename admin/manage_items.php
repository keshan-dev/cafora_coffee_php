<?php
$requiredRole = "admin";
require '../includes/auth.php';
?>

<?php
require '../includes/database_connection.php';

// ===== Add Item =====
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $badge = $_POST['badge'];
    $active = isset($_POST['active']) ? 1 : 0;

    $image_url = null;
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . basename($_FILES["image_url"]["name"]);
        move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);
        $image_url = $target_file;
    }

    $stmt = $pdo->prepare("INSERT INTO products(name, description, price, category, image_url, badge, active, created_at) VALUES(?,?,?,?,?,?,?,NOW())");
    $stmt->execute([$name, $description, $price, $category, $image_url, $badge, $active]);
    header("Location: manage_items.php"); exit;
}

// ===== Update Item =====
if (isset($_POST['update'])) {
    $id = $_POST['item_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $badge = $_POST['badge'];
    $active = isset($_POST['active']) ? 1 : 0;

    $image_url = $_POST['current_image'];
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . basename($_FILES["image_url"]["name"]);
        move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);
        $image_url = $target_file;
    }

    $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, category=?, image_url=?, badge=?, active=? WHERE id=?");
    $stmt->execute([$name, $description, $price, $category, $image_url, $badge, $active, $id]);
    header("Location: manage_items.php"); exit;
}

// ===== Delete Item =====
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
    header("Location: manage_items.php"); exit;
}

// ===== Fetch Items =====
$items = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Items</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <!-- Add Item Form -->
        <form method="POST" class="form-inline" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Item Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <select name="category" required>
            
            <option value="Coffee">Coffee</option>
            <option value="Desserts">Desserts</option>
            <option value="Soft Drinks">Soft Drinks</option>
            </select>
            <input type="text" name="badge" placeholder="Badge">
            <input type="file" name="image_url">
            <label><input type="checkbox" name="active"> Active</label>
            <button type="submit" name="add" class="btn-add" align="right">+ Add</button>
        </form>

        <!-- Items Table -->
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Badge</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                            <?php if($item['image_url']): ?>
                                <img src="<?= $item['image_url'] ?>" width="50" alt="">
                            <?php endif; ?>
                        </td>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['description'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['category'] ?></td>
                        <td><?= $item['badge'] ?></td>
                        <td><?= $item['active'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <button class="btn-edit" onclick="openModal('<?= $item['id'] ?>','<?= addslashes($item['name']) ?>','<?= addslashes($item['description']) ?>','<?= $item['price'] ?>','<?= addslashes($item['category']) ?>','<?= addslashes($item['badge']) ?>','<?= $item['active'] ?>','<?= $item['image_url'] ?>')">Edit</button>
                            <a class="btn-delete" href="manage_items.php?delete=<?= $item['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Edit Item</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="item_id" id="edit_id">
            <input type="hidden" name="current_image" id="edit_current_image">

            <label>Item Name</label>
            <input type="text" name="name" id="edit_name" placeholder="Item Name" required>

            <label>Description</label>
            <input type="text" name="description" id="edit_description" placeholder="Description" required><br><br>

            <label>Price &nbsp</label>
            <input type="number" step="0.01" name="price" id="edit_price" placeholder="Price" required><br><br>

            <label>Category</label>
            <select name="category" id="edit_category" required>
                <option value="">-- Select Category --</option>
                <option value="Coffee">Coffee</option>
                <option value="Desserts">Desserts</option>
                <option value="Soft Drinks">Soft Drinks</option>
            </select>

            <label>Badge</label>
            <input type="text" name="badge" id="edit_badge" placeholder="Badge">

            <label>Image</label>
            <input type="file" name="image_url"><br><br>

            <label><input type="checkbox" name="active" id="edit_active"> Active</label>

            <button type="submit" name="update" class="btn-add">Save</button>
        </form>
    </div>
</div>

<script>
function openModal(id,name,description,price,category,badge,active,image){
    document.getElementById('edit_id').value=id;
    document.getElementById('edit_name').value=name;
    document.getElementById('edit_description').value=description;
    document.getElementById('edit_price').value=price;
    document.getElementById('edit_category').value=category;
    document.getElementById('edit_badge').value=badge;
    document.getElementById('edit_current_image').value=image;
    document.getElementById('edit_active').checked = active == 1;
    document.getElementById('editModal').style.display='flex';
}
function closeModal(){ document.getElementById('editModal').style.display='none'; }
window.onclick=function(e){ if(e.target==document.getElementById('editModal')) closeModal(); }

const toggle = document.querySelector('.toggle');
const container = document.querySelector('.container');
toggle.addEventListener('click',()=>{ container.classList.toggle('sidebar-open'); });
</script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
