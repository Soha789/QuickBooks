<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign up — Finovo</title>
<style>
:root{--mint:#dffbe9;--mint2:#c9f4db;--green:#1f9e63;--white:#ffffff;--ink:#1b2b21}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.wrap{max-width:520px;margin:40px auto;padding:20px}
.card{background:var(--white);border:1px solid #e6f6ee;border-radius:18px;padding:22px 22px;box-shadow:0 12px 32px rgba(20,122,75,.12)}
h1{margin:0 0 12px;color:var(--green)}
label{display:block;font-weight:700;margin-top:12px}
input{width:100%;padding:12px;border:2px solid var(--mint2);border-radius:12px;margin-top:6px}
.btn{margin-top:16px;background:var(--green);color:var(--white);border:none;padding:12px 16px;border-radius:12px;font-weight:800;width:100%}
.link{display:block;margin-top:12px;text-align:center;color:var(--green);text-decoration:none;font-weight:700}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h1>Create your account</h1>
    <form method="post">
      <label>Username</label>
      <input name="username" required>
      <label>Email</label>
      <input name="email" type="email" required>
      <label>Password</label>
      <input name="password" type="password" minlength="4" required>
      <button class="btn" type="submit">Sign up</button>
    </form>
    <a class="link" href="login.php">Already have an account? Log in</a>
  </div>
</div>

<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
  $_SESSION['app']['user'] = [
    'username'=>trim($_POST['username']),
    'email'=>trim($_POST['email']),
    'password'=>$_POST['password']
  ];
  echo "<script>alert('Account created! Welcome, ".htmlspecialchars($_POST['username']).".'); window.location.href='dashboard.php';</script>";
}
?>
</body>
</html>
