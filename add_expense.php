<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $_SESSION['app']['expenses'][] = [
    'date'=>$_POST['date'],
    'category'=>trim($_POST['category']),
    'note'=>trim($_POST['note']),
    'amount'=>floatval($_POST['amount'])
  ];
  echo "<script>alert('Expense added'); window.location.href='expenses.php';</script>"; exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Expense — Finovo</title>
<style>
:root{--mint:#dffbe9;--mint2:#c9f4db;--green:#1f9e63;--white:#ffffff;--line:#e6f6ee}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.wrap{max-width:640px;margin:28px auto;padding:16px}
.card{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:18px;box-shadow:0 10px 28px rgba(20,122,75,.08)}
h2{margin:0 0 10px;color:#1f9e63}
label{display:block;font-weight:800;margin-top:10px}
input,textarea,select{width:100%;padding:12px;border:2px solid var(--mint2);border-radius:12px;margin-top:6px}
.btn{margin-top:14px;background:#1f9e63;color:#fff;border:none;padding:12px 16px;border-radius:12px;font-weight:900}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h2>New Expense</h2>
    <form method="post">
      <label>Date</label><input type="date" name="date" required value="<?php echo date('Y-m-d');?>">
      <label>Category</label>
      <select name="category" required>
        <option value="Supplies">Supplies</option>
        <option value="Utilities">Utilities</option>
        <option value="Marketing">Marketing</option>
        <option value="Travel">Travel</option>
        <option value="Other">Other</option>
      </select>
      <label>Note</label><textarea name="note" rows="3"></textarea>
      <label>Amount (SAR)</label><input type="number" name="amount" step="0.01" min="0" required>
      <button class="btn">Add Expense</button>
      <button class="btn" type="button" onclick="window.location.href='expenses.php'">Cancel</button>
    </form>
  </div>
</div>
</body>
</html>
