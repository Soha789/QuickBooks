<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
$txs =& $_SESSION['app']['transactions'];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $txs[] = [
    'type'=>$_POST['type'],
    'amount'=>floatval($_POST['amount']),
    'note'=>trim($_POST['note']),
    'date'=>$_POST['date']
  ];
  echo "<script>alert('Transaction added'); window.location.href='transactions.php';</script>"; exit;
}
$recent = array_reverse($txs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transactions — Finovo</title>
<style>
:root{--mint:#dffbe9;--mint2:#c9f4db;--green:#1f9e63;--white:#ffffff;--ink:#1b2b21;--line:#e6f6ee}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.container{max-width:1100px;margin:0 auto;padding:20px}
.nav{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between;background:var(--white);border:1px solid var(--line);border-radius:16px;padding:10px 14px;box-shadow:0 8px 24px rgba(20,122,75,.08)}
.nav a{color:var(--ink);text-decoration:none;font-weight:800;padding:8px 12px;border-radius:10px}
.nav a.active,.nav a:hover{background:#c9f4db}
.grid{display:grid;grid-template-columns:1.1fr .9fr;gap:14px;margin-top:16px}
.card{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:16px;box-shadow:0 10px 28px rgba(20,122,75,.08)}
label{display:block;font-weight:800;margin-top:10px}
input,select,textarea{width:100%;padding:12px;border:2px solid var(--mint2);border-radius:12px;margin-top:6px}
.btn{margin-top:14px;background:#1f9e63;color:#fff;border:none;padding:12px 16px;border-radius:12px;font-weight:900}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:10px;border-bottom:1px dashed #eaf8f1;text-align:left}
.badge{background:#c9f4db;padding:4px 8px;border-radius:999px;font-weight:800}
</style>
</head>
<body>
<div class="container">
  <div class="nav">
    <div style="font-weight:900;color:var(--green)">Finovo</div>
    <div>
      <a href="dashboard.php">Dashboard</a>
      <a href="invoices.php">Invoices</a>
      <a href="expenses.php">Expenses</a>
      <a href="reports.php">Reports</a>
      <a class="active" href="transactions.php">Transactions</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <h3 style="margin:0 0 6px">Send / Receive Money</h3>
      <form method="post">
        <label>Type</label>
        <select name="type" required>
          <option value="Receive">Receive (Income)</option>
          <option value="Send">Send (Expense)</option>
        </select>
        <label>Date</label><input type="date" name="date" required value="<?php echo date('Y-m-d');?>">
        <label>Amount (SAR)</label><input type="number" name="amount" step="0.01" min="0" required>
        <label>Note</label><textarea name="note" rows="2" placeholder="e.g., Client transfer / Supplier payment"></textarea>
        <button class="btn">Add Transaction</button>
      </form>
    </div>
    <div class="card">
      <h3 style="margin:0 0 6px">Recent</h3>
      <table class="table">
        <tr><th>Date</th><th>Type</th><th>Amount</th><th>Note</th></tr>
        <?php if(empty($recent)): ?>
          <tr><td colspan="4">No transactions yet.</td></tr>
        <?php else: foreach($recent as $t): ?>
          <tr>
            <td><?php echo htmlspecialchars($t['date']);?></td>
            <td><span class="badge"><?php echo htmlspecialchars($t['type']);?></span></td>
            <td><?php echo 'SAR '.number_format($t['amount'],2);?></td>
            <td><?php echo htmlspecialchars($t['note']);?></td>
          </tr>
        <?php endforeach; endif;?>
      </table>
      <div style="margin-top:8px"><a class="badge" href="dashboard.php">Back to Dashboard</a></div>
    </div>
  </div>
</div>
</body>
</html>
