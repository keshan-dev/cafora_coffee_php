<?php
$requiredRole = "admin"; // only admins can access
require '../includes/auth.php';
require '../includes/database_connection.php';

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
            <h2>Messages</h2>
        </div>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="flash"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
        <?php endif; ?>

        <!-- Messages Table -->
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
                                <button class="btn-view" onclick="openModal('view-<?= $msg['id'] ?>')">View</button>
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
                                <p><a class="btn-view" href="mailto:<?= htmlspecialchars($msg['email']) ?>">Reply via Email</a></p>
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

<script>
const toggle = document.querySelector('.toggle');
const container = document.querySelector('.container');
toggle.addEventListener('click',()=>{ container.classList.toggle('sidebar-open'); });

function openModal(id){ document.getElementById(id).style.display="flex"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }
</script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
