<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
$app =& $_SESSION['app'];
$invoices = $app['invoices'] ?? [];
$expenses = $app['expenses'] ?? [];
$txs = $app['transactions'] ?? [];

// Totals
$incomeFromInvoices = 0;
foreach($invoices as $inv){ if(($inv['status'] ?? 'Unpaid')==='Paid'){ $incomeFromInvoices += floatval($inv['amount']); } }
$incomeFromReceives = 0;
$expenseFromSends = 0;
foreach($txs as $t){
  if($t['type']==='Receive') $incomeFromReceives += floatval($t['amount']);
  if($t['type']==='Send') $expenseFromSends += floatval($t['amount']);
}
$totalIncome = $incomeFromInvoices + $incomeFromReceives;
$totalExpenses = array_sum(array_map(fn($e)=>floatval($e['amount']), $expenses)) + $expenseFromSends;
$balance = $totalIncome - $totalExpenses;

$recent = array_slice(array_reverse($txs), 0, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Finovo</title>
<style>
:root{--mint:#dffbe9;--green:#1f9e63;--white:#ffffff;--ink:#1b2b21;--line:#e6f6ee}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.container{max-width:1100px;margin:0 auto;padding:20px}
.nav{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between;background:var(--white);border:1px solid var(--line);border-radius:16px;padding:10px 14px;box-shadow:0 8px 24px rgba(20,122,75,.08)}
.nav a{color:var(--ink);text-decoration:none;font-weight:800;padding:8px 12px;border-radius:10px}
.nav a.active,.nav a:hover{background:#c9f4db}
.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:16px}
.card{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:16px;box-shadow:0 10px 28px rgba(20,122,75,.08)}
.kpi{font-size:28px;font-weight:900;color:var(--green)}
.list{list-style:none;padding:0;margin:0}
.item{display:flex;justify-content:space-between;border-bottom:1px dashed #eaf8f1;padding:8px 0}
.badge{padding:4px 8px;border-radius:999px;font-size:12px;font-weight:800;background:#c9f4db}
</style>
</head>
<body>
<div class="container">
  <div class="nav">
    <div style="font-weight:900;color:var(--green)">Finovo</div>
    <div>
      <a class="active" href="dashboard.php">Dashboard</a>
      <a href="invoices.php">Invoices</a>
      <a href="expenses.php">Expenses</a>
      <a href="reports.php">Reports</a>
      <a href="transactions.php">Transactions</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <div>Total Income</div>
      <div class="kpi">SAR <?php echo number_format($totalIncome,2); ?></div>
      <small>Invoices (Paid) + Receives</small>
    </div>
    <div class="card">
      <div>Total Expenses</div>
      <div class="kpi">SAR <?php echo number_format($totalExpenses,2); ?></div>
      <small>Expenses + Sends</small>
    </div>
    <div class="card">
      <div>Balance</div>
      <div class="kpi">SAR <?php echo number_format($balance,2); ?></div>
      <small>Income − Expenses</small>
    </div>
  </div>

  <div class="grid" style="grid-template-columns:2fr 1fr">
    <div class="card">
      <h3 style="margin:0 0 8px">Recent Transactions</h3>
      <ul class="list">
        <?php if(!$recent): ?>
          <li class="item"><span>No transactions yet.</span></li>
        <?php else: foreach($recent as $r): ?>
          <li class="item">
            <span><strong><?php echo htmlspecialchars($r['type']);?></strong> — <?php echo htmlspecialchars($r['note']??''); ?></span>
            <span>SAR <?php echo number_format($r['amount'],2);?></span>
          </li>
        <?php endforeach; endif;?>
      </ul>
      <div style="margin-top:10px"><a class="badge" href="transactions.php">Add transaction</a></div>
    </div>
    <div class="card">
      <h3 style="margin:0 0 8px">Quick Actions</h3>
      <div style="display:flex;flex-direction:column;gap:8px">
        <a class="badge" href="create_invoice.php">Create Invoice</a>
        <a class="badge" href="add_expense.php">Add Expense</a>
        <a class="badge" href="reports.php">View Reports</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
