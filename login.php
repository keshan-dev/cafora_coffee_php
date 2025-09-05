<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register & Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <!-- Register -->
  <div class="container" id="signup">
    <h1 class="form-title">Register</h1>
    <form method="post" action="register.php">
      <div class="input-group"> 
        <i class="fas fa-user"></i>
        <input type="text" name="fName" id="fName" placeholder="User Name" required>
        <label for="fName">User Name</label>
      </div>

      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="emailUp" placeholder="Email" required>
        <label for="emailUp">Email</label>
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="passwordUp" placeholder="Password" required>
        <label for="passwordUp">Password</label>
      </div>

      <input type="submit" class="btn" value="Sign Up" name="signUp">
    </form>

    <p class="or">---------- or --------</p>
    <div class="icons">
      <i class="fab fa-google" aria-hidden="true" title="Sign up with Google"></i>
      <i class="fab fa-facebook" aria-hidden="true" title="Sign up with Facebook"></i>
    </div>

    <div class="links">
      <p>Already have an account?</p>
      <button id="signInButton" type="button">Sign In</button>
    </div>
  </div>

  <!-- Sign In -->
  <div class="container" id="signIn">
    <h1 class="form-title">Sign In</h1>
    <form method="post" action="register.php">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="emailIn" placeholder="Email" required>
        <label for="emailIn">Email</label>
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="passwordIn" placeholder="Password" required>
        <label for="passwordIn">Password</label>
      </div>

      <p class="recover"><a href="#">Recover Password</a></p>
      <input type="submit" class="btn" value="Sign In" name="signIn">
    </form>

    <p class="or">---------- or --------</p>
    <div class="icons">
      <i class="fab fa-google" aria-hidden="true" title="Sign in with Google"></i>
      <i class="fab fa-facebook" aria-hidden="true" title="Sign in with Facebook"></i>
    </div>

    <div class="links">
      <p>Don't have an account yet?</p>
      <button id="signUpButton" type="button">Sign Up</button>
    </div>
  </div>

  <script src="assets/js/register.js"></script>
</body>
</html>
