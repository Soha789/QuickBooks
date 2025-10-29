<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
$u =& $_SESSION['app']['user'];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $u['username'] = trim($_POST['username']);
  $u['email']    = trim($_POST['email']);
  echo "<script>alert('Profile updated'); window.location.href='profile.php';</script>"; exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile — Finovo</title>
<style>
:root{--mint:#dffbe9;--mint2:#c9f4db;--green:#1f9e63;--white:#ffffff;--ink:#1b2b21;--line:#e6f6ee}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.wrap{max-width:640px;margin:28px auto;padding:16px}
.nav{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between;background:var(--white);border:1px solid var(--line);border-radius:16px;padding:10px 14px;box-shadow:0 8px 24px rgba(20,122,75,.08)}
.nav a{color:var(--ink);text-decoration:none;font-weight:800;padding:8px 12px;border-radius:10px}
.nav a.active,.nav a:hover{background:#c9f4db}
.card{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:18px;box-shadow:0 10px 28px rgba(20,122,75,.08)}
label{display:block;font-weight:800;margin-top:10px}
input{width:100%;padding:12px;border:2px solid var(--mint2);border-radius:12px;margin-top:6px}
.btn{margin-top:14px;background:#1f9e63;color:#fff;border:none;padding:12px 16px;border-radius:12px;font-weight:900}
</style>
</head>
<body>
<div class="wrap">
  <div class="nav">
    <div style="font-weight:900;color:#1f9e63">Finovo</div>
    <div>
      <a href="dashboard.php">Dashboard</a>
      <a href="invoices.php">Invoices</a>
      <a href="expenses.php">Expenses</a>
      <a href="reports.php">Reports</a>
      <a href="transactions.php">Transactions</a>
      <a class="active" href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="card">
    <h2 style="margin:0 0 8px;color:#1f9e63">Edit Profile</h2>
    <form method="post">
      <label>Username</label><input name="username" value="<?php echo htmlspecialchars($u['username']);?>" required>
      <label>Email</label><input name="email" type="email" value="<?php echo htmlspecialchars($u['email']);?>" required>
      <button class="btn">Save Changes</button>
    </form>
  </div>
</div>
</body>
</html>
8 
