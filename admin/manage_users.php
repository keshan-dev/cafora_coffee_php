<?php
$requiredRole = "admin";
require '../includes/auth.php';
?>

<?php
require '../includes/database_connection.php';

// Add User
if(isset($_POST['add'])){
    $name=$_POST['name']; $email=$_POST['email'];
    $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
    $role=$_POST['role'];
    $stmt=$pdo->prepare("INSERT INTO users(name,email,password,role,created_at,status) VALUES(?,?,?,?,NOW(),'active')");
    $stmt->execute([$name,$email,$password,$role]);
    header("Location: manage_users.php"); exit;
}

// Update User
if(isset($_POST['update'])){
    $id=$_POST['user_id']; $name=$_POST['name']; $role=$_POST['role']; $status=$_POST['status'];
    $stmt=$pdo->prepare("UPDATE users SET name=?, role=?, status=? WHERE user_id=?");
    $stmt->execute([$name,$role,$status,$id]);
    header("Location: manage_users.php"); exit;
}

// Delete User
if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    $stmt=$pdo->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->execute([$id]);
    header("Location: manage_users.php"); exit;
}

// Fetch Users
$users=$pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
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

        <!-- Add User Form -->
        <form method="POST" class="form-inline">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add" class="btn-add">+ Add</button>
        </form>

        <!-- Users Table -->
        <div class="table-wrapper">
            <table class="styled-table">
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['status'] ?></td>
                        <td>
                            <button class="btn-edit" onclick="openModal('<?= $user['user_id'] ?>','<?= $user['name'] ?>','<?= $user['email'] ?>','<?= $user['role'] ?>','<?= $user['status'] ?>')">Edit</button>
                            <a class="btn-delete" href="manage_users.php?delete=<?= $user['user_id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
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
        <h3>Edit User</h3>
        <form method="POST">
            <input type="hidden" name="user_id" id="edit_id">
            <input type="text" name="name" id="edit_name" placeholder="Name" required>
            <input type="email" name="email" id="edit_email" placeholder="Email" readonly>
            <select name="role" id="edit_role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <select name="status" id="edit_status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="blocked">Blocked</option>
            </select>
            <button type="submit" name="update" class="btn-add">Save</button>
        </form>
    </div>
</div>

<script>
function openModal(id,name,email,role,status){
    document.getElementById('edit_id').value=id;
    document.getElementById('edit_name').value=name;
    document.getElementById('edit_email').value=email;
    document.getElementById('edit_role').value=role;
    document.getElementById('edit_status').value=status;
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
