<?php
session_start();
require 'includes/database_connection.php';

$flash = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $website = trim($_POST['website']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    try {
        $sql = "INSERT INTO contact (name, email, phone, website, subject, message) 
                VALUES (:name, :email, :phone, :website, :subject, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':phone'   => $phone,
            ':website' => $website,
            ':subject' => $subject,
            ':message' => $message
        ]);
        $flash = "✅ Message sent!";
    } catch (PDOException $e) {
        $flash = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cafora — Contact</title>

  <link rel="stylesheet" href="/cafora_coffee_php/assets/css/font.css">
  <link rel="stylesheet" href="/cafora_coffee_php/assets/css/contact.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Fixed Header -->
<header class="site-header">
  <div class="nav-container">
    <div class="logo"><a href="/cafora_coffee_php/index.php">Cafora</a></div>
    <nav class="nav-links">
      <a href="/cafora_coffee_php/index.php">HOME</a>
      <a href="/cafora_coffee_php/product.php">SHOP</a>
      <a href="/cafora_coffee_php/users/cart.php">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="m1 1 4 4 5.9 13 9.1-13H8.5"></path>
        </svg>
        CART
      </a>
      <a href="/cafora_coffee_php/login.php">LOGIN</a>
    </nav>
  </div>
</header>

<!-- Hero Cover -->
<section class="hero-section">
  <img src="assets/images/banner.jpg" alt="Cover">
  <div class="hero-content">
    <h1>Contact Us</h1>
  </div>
</section>

<div class="contact-wrap">
  <div class="contact-grid">
    <!-- Info -->
    <aside class="info-card">
      <small>LET’S TALK</small>
      <h2>Speak With Expert Engineers.</h2>
      <div class="info-item"><strong>Email:</strong><br>info@gsit.com.au</div>
      <div class="info-item"><strong>Phone:</strong><br>AU: +61 (02) 844 302 41<br>LK: +94 11 2820206</div>
      <div class="info-item"><strong>Address:</strong><br>17/3, Sarasavi Mawatha,<br>Kalubowila, Colombo, Sri Lanka.</div>
    </aside>

    <!-- Form -->
    <section class="form-card">
      <div class="form-sub">GET IN TOUCH</div>
      <h3>Fill The Form Below</h3>
      <?php if ($flash): ?>
        <div class="flash"><?php echo htmlspecialchars($flash); ?></div>
      <?php endif; ?>

      <form method="POST" class="contact-form">
        <div class="grid-2">
          <input class="input" type="text" name="name" placeholder="Name" required>
          <input class="input" type="email" name="email" placeholder="E-Mail" required>
        </div>
        <div class="grid-2">
          <input class="input" type="text" name="phone" placeholder="Phone Number">
          <input class="input" type="text" name="website" placeholder="Your Website">
        </div>
        <input class="input" type="text" name="subject" placeholder="Subject" required>
        <textarea class="textarea" name="message" placeholder="Your Message Here" required></textarea>
        <button class="btn" type="submit">Submit Now</button>
      </form>
    </section>
  </div>

  <!-- Map -->
  <div class="map-wrap">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.575840369662!2d80.03899797480865!3d6.821329093176432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2523b05555555%3A0x546c34cd99f6f488!2sNSBM%20Green%20University!5e0!3m2!1sen!2slk!4v1757352331758!5m2!1sen!2slk" loading="lazy"></iframe>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
