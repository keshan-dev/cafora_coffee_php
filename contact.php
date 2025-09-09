<?php
session_start();

// DB connect
$conn = mysqli_connect("localhost", "root", "", "coffee_shop");
if (!$conn) { die("DB connection failed: " . mysqli_connect_error()); }

$flash = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact (name,email,phone,website,subject,message)
            VALUES ('$name','$email','$phone','$address','$subject','$message')";
    $flash = mysqli_query($conn,$sql) ? " Message sent!" : (" Error: ".mysqli_error($conn));
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

<!-- Header -->
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

  <a href="/cafora_coffee_php/login.php">LOGIN</a>

</nav>



  </div>
</header>

<!-- Hero Cover -->
<section class="hero-section">
  <!-- Replace with your cover image -->
  <img src="uploads/barista-station.jpg" alt="Cover">
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
      <div class="info-item"><strong>Email:</strong><br>info@gsit.com.lk</div>
      <div class="info-item"><strong>Phone:</strong><br>Tel: 0113657867<br>whatsapp: +94 11 2820206</div>
      <div class="info-item"><strong>Address:</strong><br>Pitipana - Thalagala Rd,<br>Homagama, Colombo, Sri Lanka.</div>
    </aside>

    <!-- Form -->
    <section class="form-card">
      <div class="form-sub">GET IN TOUCH</div>
      <h3>Fill The Form Below</h3>
      <?php if($flash): ?><div class="flash"><?php echo htmlspecialchars($flash); ?></div><?php endif; ?>

      <form method="POST" class="contact-form">
        <div class="grid-2">
          <input class="input" type="text" name="name" placeholder="First Name" required>
         
          <input class="input" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="grid-2">
             <input class="input" type="text" name="address" placeholder="Address (optional)" required>
          <input class="input" type="text" name="phone" placeholder="Phone">
          
        </div>
        <input class="input" type="text" name="subject" placeholder="Subject" required>
        <textarea class="textarea" name="message" placeholder="Your Message Here" required></textarea>
        <button class="btn" type="submit">Submit</button>
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
