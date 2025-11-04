<?php
session_start();

// If already logged in, go straight to dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php"); // Make sure dashboard.php exists
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background-color:#F3F3F3; display:flex; justify-content:center; align-items:center; height:100vh;">

  <div style="background-color:#FFFFFF; padding:50px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.05); width:380px; text-align:center;">
    
    <div style="font-size:50px; margin-bottom:20px; color:#C6F53A;">⬤</div>
    <h2 style="margin:0; color:#000;">Welcome Back!</h2>
    <p style="color:#555; font-size:14px; margin-bottom:25px;">Let's get you signed in securely.</p>

    <form method="POST" action="login_process.php">
      <div style="text-align:left; font-size:13px; color:#000;">Email</div>
      <input type="email" name="email" placeholder="Enter Your Email Address" required
             style="width:100%; padding:10px; margin:8px 0 15px; border:1px solid #E0E0E0; border-radius:5px; background-color:#F9F9F9; color:#000;">

      <div style="display:flex; justify-content:space-between; align-items:center;">
        <label style="font-size:13px; color:#000;">Password</label>
        <a href="#" style="font-size:12px; color:#000; text-decoration:none;">Forgot Your Password?</a>
      </div>
      <input type="password" name="password" placeholder="Your Password" required
             style="width:100%; padding:10px; margin:8px 0 20px; border:1px solid #E0E0E0; border-radius:5px; background-color:#F9F9F9; color:#000;">

      <button type="submit" style="width:100%; padding:10px; background:#C6F53A; color:#000; border:none; border-radius:5px; cursor:pointer; margin-bottom:15px; font-weight:bold;">
        Log in with Email
      </button>
    </form>

    <?php
    // Display error message if login fails
    if (isset($_GET['error'])) {
        echo '<p style="color:red; font-size:13px;">Invalid email or password.</p>';
    }
    ?>

    <p style="font-size:13px; color:#888;">Don’t Have an Account? 
      <a href="signup.php" style="color:#000; text-decoration:none; font-weight:bold;">Sign Up</a>
    </p>

  </div>

</body>
</html>
