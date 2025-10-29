<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
$expenses =& $_SESSION['app']['expenses'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expenses — Finovo</title>
<style>
:root{--mint:#dffbe9;--mint2:#c9f4db;--green:#1f9e63;--white:#ffffff;--ink:#1b2b21;--line:#e6f6ee}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.container{max-width:1100px;margin:0 auto;padding:20px}
.nav{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between;background:var(--white);border:1px solid var(--line);border-radius:16px;padding:10px 14px;box-shadow:0 8px 24px rgba(20,122,75,.08)}
.nav a{color:var(--ink);text-decoration:none;font-weight:800;padding:8px 12px;border-radius:10px}
.nav a.active,.nav a:hover{background:#c9f4db}
.card{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:16px;box-shadow:0 10px 28px rgba(20,122,75,.08)}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:12px;border-bottom:1px dashed #eaf8f1;text-align:left}
.btn{padding:8px 12px;border-radius:10px;font-weight:800;border:none;background:var(--green);color:#fff}
.head{display:flex;justify-content:space-between;align-items:center}
</style>
</head>
<body>
<div class="container">
  <div class="nav">
    <div style="font-weight:900;color:var(--green)">Finovo</div>
    <div>
      <a href="dashboard.php">Dashboard</a>
      <a href="invoices.php">Invoices</a>
      <a class="active" href="expenses.php">Expenses</a>
      <a href="reports.php">Reports</a>
      <a href="transactions.php">Transactions</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="card">
    <div class="head">
      <h2 style="margin:0">Expenses</h2>
      <button class="btn" onclick="window.location.href='add_expense.php'">Add Expense</button>
    </div>
    <table class="table">
      <tr><th>Date</th><th>Category</th><th>Note</th><th>Amount (SAR)</th></tr>
      <?php if(empty($expenses)): ?>
        <tr><td colspan="4">No expenses yet.</td></tr>
      <?php else: foreach($expenses as $e): ?>
        <tr>
          <td><?php echo htmlspecialchars($e['date']);?></td>
          <td><?php echo htmlspecialchars($e['category']);?></td>
          <td><?php echo htmlspecialchars($e['note']);?></td>
          <td><?php echo number_format($e['amount'],2);?></td>
        </tr>
      <?php endforeach; endif;?>
    </table>
  </div>
</div>
</body>
</html>
