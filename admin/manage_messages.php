<?php
$requiredRole = "admin"; // only admins can access
require '../includes/auth.php';
require '../includes/database_connection.php';
// session_start();

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['flash'] = "✅ Message deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['flash'] = "❌ Error deleting: " . $e->getMessage();
    }
    header("Location: manage_messages.php");
    exit;
}

// --- Handle Update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_message'])) {
    $id      = intval($_POST['id']);
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $website = trim($_POST['website']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    try {
        $sql = "UPDATE contact SET 
                    name = :name, email = :email, phone = :phone, 
                    website = :website, subject = :subject, message = :message 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id'      => $id,
            ':name'    => $name,
            ':email'   => $email,
            ':phone'   => $phone,
            ':website' => $website,
            ':subject' => $subject,
            ':message' => $message
        ]);
        $_SESSION['flash'] = "✅ Message updated successfully.";
    } catch (PDOException $e) {
        $_SESSION['flash'] = "❌ Error updating: " . $e->getMessage();
    }
    header("Location: manage_messages.php");
    exit;
}

// --- Fetch Messages ---
try {
    $stmt = $pdo->query("SELECT * FROM contact ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Messages</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="container">
    <?php include "nav.php"; ?>
    <div class="main">
        <div class="topbar">
            <div class="toggle">&#9776;</div>
            <h2>Cafora_Coffee Messages</h2>
        </div>

        <div class="content">
            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="flash"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
            <?php endif; ?>

            <div class="table-wrapper">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($messages): ?>
                            <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?= $msg['id'] ?></td>
                                <td><?= htmlspecialchars($msg['name']) ?></td>
                                <td><?= htmlspecialchars($msg['email']) ?></td>
                                <td><?= htmlspecialchars($msg['subject']) ?></td>
                                <td><?= $msg['created_at'] ?></td>
                                <td>
                                    <button class="btn-edit" onclick="openModal('view-<?= $msg['id'] ?>')">View</button>
                                    <button class="btn-edit" onclick="openModal('edit-<?= $msg['id'] ?>')">Edit</button>
                                    <a class="btn-delete" href="manage_messages.php?delete=<?= $msg['id'] ?>" onclick="return confirm('Delete this message?')">Delete</a>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div id="view-<?= $msg['id'] ?>" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal('view-<?= $msg['id'] ?>')">&times;</span>
                                    <h3>Message from <?= htmlspecialchars($msg['name']) ?></h3>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
                                    <p><strong>Phone:</strong> <?= htmlspecialchars($msg['phone']) ?></p>
                                    <p><strong>Website:</strong> <?= htmlspecialchars($msg['website']) ?></p>
                                    <p><strong>Subject:</strong> <?= htmlspecialchars($msg['subject']) ?></p>
                                    <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                    <p><a class="btn-edit" href="mailto:<?= htmlspecialchars($msg['email']) ?>">Reply via Email</a></p>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div id="edit-<?= $msg['id'] ?>" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal('edit-<?= $msg['id'] ?>')">&times;</span>
                                    <h3>Edit Message</h3>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?= htmlspecialchars($msg['name']) ?>" required>
                                        <label>Email</label>
                                        <input type="email" name="email" value="<?= htmlspecialchars($msg['email']) ?>" required>
                                        <label>Phone</label>
                                        <input type="text" name="phone" value="<?= htmlspecialchars($msg['phone']) ?>">
                                        <label>Website</label>
                                        <input type="text" name="website" value="<?= htmlspecialchars($msg['website']) ?>">
                                        <label>Subject</label>
                                        <input type="text" name="subject" value="<?= htmlspecialchars($msg['subject']) ?>" required>
                                        <label>Message</label>
                                        <textarea name="message" required><?= htmlspecialchars($msg['message']) ?></textarea>
                                        <button type="submit" name="update_message" class="btn-add">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No messages found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const toggle = document.querySelector('.toggle');
const container = document.querySelector('.container');
toggle.addEventListener('click',()=>{ container.classList.toggle('sidebar-open'); });

function openModal(id){ document.getElementById(id).style.display="flex"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }
</script>
</body>
</html>
