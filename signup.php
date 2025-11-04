<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Page</title>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background-color:#F3F3F3; display:flex; justify-content:center; align-items:center; height:100vh;">

  <div style="background-color:#FFFFFF; padding:50px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.05); width:400px; text-align:center;">
    
    <!-- Logo -->
    <div style="font-size:50px; margin-bottom:20px; color:#C6F53A;">⬤</div>

    <!-- Heading -->
    <h2 style="margin:0; color:#000;">Create Account</h2>
    <p style="color:#555; font-size:14px; margin-bottom:25px;">Join as a student and get started!</p>

    <!-- ✅ FORM starts here -->
    <form action="signup.php" method="POST">

      <!-- Student ID -->
      <div style="text-align:left; font-size:13px; color:#000;">Student ID</div>
      <input type="text" name="student_id" placeholder="Enter Your Student ID"
             required
             style="width:100%; padding:10px; margin:8px 0 15px; border:1px solid #E0E0E0; border-radius:5px; background-color:#F9F9F9; color:#000;">

      <!-- Email -->
      <div style="text-align:left; font-size:13px; color:#000;">Email</div>
      <input type="email" name="email" placeholder="Enter Your Email"
             required
             style="width:100%; padding:10px; margin:8px 0 15px; border:1px solid #E0E0E0; border-radius:5px; background-color:#F9F9F9; color:#000;">

      <!-- Password -->
      <div style="text-align:left; font-size:13px; color:#000;">Password</div>
      <input type="password" name="password" placeholder="Create Password"
             required
             style="width:100%; padding:10px; margin:8px 0 15px; border:1px solid #E0E0E0; border-radius:5px; background-color:#F9F9F9; color:#000;">

      <!-- Department -->
      <div style="text-align:left; font-size:13px; color:#000;">Department</div>
      <select name="department"
              required
              style="width:100%; padding:10px; margin:8px 0 25px; border:1px solid #E0E0E0; border-radius:5px; background-color:#F9F9F9; color:#000;">
        <option value="" disabled selected>Select Department</option>
        <option value="MCA">MCA</option>
        <option value="Information Technology">Information Technology</option>
        <option value="CIVIL">CIVIL</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Commerce">Electronics</option>
      </select>

      <!-- Sign Up Button -->
      <button type="submit" 
              style="width:100%; padding:10px; background:#C6F53A; color:#000; border:none; border-radius:5px; cursor:pointer; margin-bottom:15px; font-weight:bold;">
        Sign Up
      </button>
    </form>
    <!-- ✅ FORM ends here -->

    <!-- Login Link -->
    <p style="font-size:13px; color:#888;">Already have an account? 
      <a href="login.html" style="color:#000; text-decoration:none; font-weight:bold;">Log In</a>
    </p>

  </div>

</body>
</html>
