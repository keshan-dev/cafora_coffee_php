<?php
session_start();
require '../includes/database_connection.php'; // adjust path if needed

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

// --- Handle Update (Edit form submission) ---
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cafora — Manage Messages</title>

  <link rel="stylesheet" href="/cafora_coffee_php/assets/css/font.css">
  <link rel="stylesheet" href="/cafora_coffee_php/assets/css/admin.css"> <!-- same theme as manage_users -->
</head>
<body>

<?php include 'includes/admin_header.php'; ?> <!-- same admin header/navigation -->

<div class="admin-container">
  <h1>📩 Manage Messages</h1>

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>

  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Subject</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($messages): ?>
        <?php foreach ($messages as $msg): ?>
          <tr>
            <td><?php echo $msg['id']; ?></td>
            <td><?php echo htmlspecialchars($msg['name']); ?></td>
            <td><?php echo htmlspecialchars($msg['email']); ?></td>
            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
            <td><?php echo $msg['created_at']; ?></td>
            <td>
              <!-- View -->
              <button onclick="openModal(<?php echo $msg['id']; ?>)">View</button>
              <!-- Edit -->
              <button onclick="openEdit(<?php echo $msg['id']; ?>)">Edit</button>
              <!-- Delete -->
              <a href="manage_messages.php?delete=<?php echo $msg['id']; ?>" onclick="return confirm('Delete this message?')">Delete</a>
            </td>
          </tr>

          <!-- View Modal -->
          <div id="view-<?php echo $msg['id']; ?>" class="modal">
            <div class="modal-content">
              <span class="close" onclick="closeModal(<?php echo $msg['id']; ?>)">&times;</span>
              <h2>Message from <?php echo htmlspecialchars($msg['name']); ?></h2>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?></p>
              <p><strong>Phone:</strong> <?php echo htmlspecialchars($msg['phone']); ?></p>
              <p><strong>Website:</strong> <?php echo htmlspecialchars($msg['website']); ?></p>
              <p><strong>Subject:</strong> <?php echo htmlspecialchars($msg['subject']); ?></p>
              <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
            </div>
          </div>

          <!-- Edit Modal -->
          <div id="edit-<?php echo $msg['id']; ?>" class="modal">
            <div class="modal-content">
              <span class="close" onclick="closeEdit(<?php echo $msg['id']; ?>)">&times;</span>
              <h2>Edit Message</h2>
              <form method="POST">
                <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($msg['name']); ?>" required>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($msg['email']); ?>" required>
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($msg['phone']); ?>">
                <label>Website</label>
                <input type="text" name="website" value="<?php echo htmlspecialchars($msg['website']); ?>">
                <label>Subject</label>
                <input type="text" name="subject" value="<?php echo htmlspecialchars($msg['subject']); ?>" required>
                <label>Message</label>
                <textarea name="message" required><?php echo htmlspecialchars($msg['message']); ?></textarea>
                <button type="submit" name="update_message">Save Changes</button>
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

<script>
function openModal(id) { document.getElementById("view-"+id).style.display = "block"; }
function closeModal(id) { document.getElementById("view-"+id).style.display = "none"; }
function openEdit(id) { document.getElementById("edit-"+id).style.display = "block"; }
function closeEdit(id) { document.getElementById("edit-"+id).style.display = "none"; }
</script>

<?php include 'includes/admin_footer.php'; ?>
</body>
</html>
