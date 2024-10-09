<?php
// Include the PHP logic from the external file
include 'php/welcome.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="icon" type="image/x-icon" href="homelet_log1.png">
    <link rel="stylesheet" href="signup.css">
</head>
<body>
<header>
  <h1 style="font-size: 60px;">Homelet</h1>
</header>
<section>
  <div class="section1">
    <p style="font-size: 20px;">Please fill in this form to create an account.</p><hr>
  </div>
  <div class="section2">
    <div class="form">
      <h2 style="font-size: 30px;">Create Account</h2>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        
        <label for="fname">Full Name</label><br>
        <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname);?>"><br>
        <span class="error"><?php echo $fnameErr;?></span><br>
        
        <label for="email">Email Address</label><br>
        <input type="email" id="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($email);?>"><br>
        <span class="error"><?php echo $emailErr;?></span><br>

        <label for="pwd">Password</label><br>
        <input type="password" id="pwd" name="pwd"><br>
        <span class="error"><?php echo $pwdErr;?></span><br>
        
        <label for="pwd_confirm">Confirm Password</label><br>
        <input type="password" id="pwd_confirm" name="pwd_confirm" onblur="validateForm()"><br>
        <span id="responseText"></span><br>
        
        <!-- Checkbox for showing passwords -->
        <input type="checkbox" id="showPasswords" onclick="togglePasswordVisibility()"> Show Passwords
        <br><br><hr>

        <p>By creating an account you agree to our <a href="#" style="color:#dd8d0c; text-decoration: none;">Terms & Privacy</a>.</p>
        <input type="submit" value="Submit">
      </form>
      <div class="containerSignin">
        <p>Already have an account? <a href="log.html" style="color:#dd8d0c; text-decoration: none;">Log in</a> and continue.</p>
      </div>
    </div>
  </div>
</section>
<script src="scripts/forms.js"></script>
</body>
</html>