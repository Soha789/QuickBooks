<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
$app = $_SESSION['app'];
$invoices = $app['invoices'] ?? [];
$expenses = $app['expenses'] ?? [];
$txs = $app['transactions'] ?? [];

// Build monthly arrays (1..12) current year
$y = date('Y');
$incomeMonthly = array_fill(1,12,0.0);
$expenseMonthly = array_fill(1,12,0.0);

foreach($invoices as $inv){
  $m = intval(date('n', strtotime($inv['date'])));
  $yy = date('Y', strtotime($inv['date']));
  if($yy==$y && ($inv['status']??'Unpaid')==='Paid'){ $incomeMonthly[$m]+=floatval($inv['amount']); }
}
foreach($expenses as $e){
  $m = intval(date('n', strtotime($e['date'])));
  $yy = date('Y', strtotime($e['date']));
  if($yy==$y){ $expenseMonthly[$m]+=floatval($e['amount']); }
}
foreach($txs as $t){
  $m = intval(date('n', strtotime($t['date'])));
  $yy = date('Y', strtotime($t['date']));
  if($yy==$y){
    if($t['type']==='Receive') $incomeMonthly[$m]+=floatval($t['amount']);
    if($t['type']==='Send')    $expenseMonthly[$m]+=floatval($t['amount']);
  }
}

$totalIncome = array_sum($incomeMonthly);
$totalExpense = array_sum($expenseMonthly);
$netWorth = $totalIncome - $totalExpense;

// Yearly (very simple: current vs previous year sums)
$incomeYearly = [date('Y',strtotime('-1 year'))=>0,date('Y')=>0];
$expenseYearly= [date('Y',strtotime('-1 year'))=>0,date('Y')=>0];

foreach($invoices as $inv){
  $yy = date('Y', strtotime($inv['date']));
  if(($inv['status']??'Unpaid')==='Paid' && isset($incomeYearly[$yy])) $incomeYearly[$yy]+=floatval($inv['amount']);
}
foreach($expenses as $e){
  $yy = date('Y', strtotime($e['date']));
  if(isset($expenseYearly[$yy])) $expenseYearly[$yy]+=floatval($e['amount']);
}
foreach($txs as $t){
  $yy = date('Y', strtotime($t['date']));
  if(isset($incomeYearly[$yy]) && $t['type']==='Receive') $incomeYearly[$yy]+=floatval($t['amount']);
  if(isset($expenseYearly[$yy]) && $t['type']==='Send')   $expenseYearly[$yy]+=floatval($t['amount']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports — Finovo</title>
<style>
:root{--mint:#dffbe9;--mint2:#c9f4db;--green:#1f9e63;--white:#ffffff;--ink:#1b2b21;--line:#e6f6ee;--red:#e64545}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui;background:linear-gradient(180deg,var(--mint),var(--white) 70%)}
.container{max-width:1100px;margin:0 auto;padding:20px}
.nav{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between;background:var(--white);border:1px solid var(--line);border-radius:16px;padding:10px 14px;box-shadow:0 8px 24px rgba(20,122,75,.08)}
.nav a{color:var(--ink);text-decoration:none;font-weight:800;padding:8px 12px;border-radius:10px}
.nav a.active,.nav a:hover{background:#c9f4db}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:16px}
.card{background:var(--white);border:1px solid var(--line);border-radius:16px;padding:16px;box-shadow:0 10px 28px rgba(20,122,75,.08)}
h3{margin:0 0 8px}
.kpi{font-size:24px;font-weight:900;color:var(--green)}
.canvas-wrap{background:#f6fffa;border:1px dashed #cdeedd;border-radius:14px;padding:10px}
.legend{display:flex;gap:12px;align-items:center;margin-top:6px}
.dot{width:10px;height:10px;border-radius:50%}
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
      <a class="active" href="reports.php">Reports</a>
      <a href="transactions.php">Transactions</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <h3>Monthly Overview (<?php echo date('Y');?>)</h3>
      <div class="kpi">Net Worth: SAR <?php echo number_format($netWorth,2);?></div>
      <div class="canvas-wrap"><canvas id="monthly" width="520" height="280"></canvas></div>
      <div class="legend">
        <span class="dot" style="background:#1f9e63"></span> Income
        <span class="dot" style="background:#e64545"></span> Expense
      </div>
    </div>
    <div class="card">
      <h3>Yearly Comparison</h3>
      <div class="canvas-wrap"><canvas id="yearly" width="520" height="280"></canvas></div>
      <div class="legend">
        <span class="dot" style="background:#1f9e63"></span> Income
        <span class="dot" style="background:#e64545"></span> Expense
      </div>
    </div>
  </div>
</div>

<script>
// Data from PHP
const incomeMonthly = <?php echo json_encode(array_values($incomeMonthly));?>;
const expenseMonthly= <?php echo json_encode(array_values($expenseMonthly));?>;
const yearlyLabels  = <?php echo json_encode(array_keys($incomeYearly));?>;
const incomeYearly  = <?php echo json_encode(array_values($incomeYearly));?>;
const expenseYearly = <?php echo json_encode(array_values($expenseYearly));?>;

// Simple line drawer (Income: green, Expense: red)
function drawLineChart(canvasId, labels, series1, series2){
  const c = document.getElementById(canvasId);
  const ctx = c.getContext('2d');
  const W=c.width,H=c.height, pad=36;
  ctx.clearRect(0,0,W,H);
  // axes
  ctx.strokeStyle="#b6e9cd"; ctx.lineWidth=2;
  ctx.beginPath(); ctx.moveTo(pad, pad); ctx.lineTo(pad, H-pad); ctx.lineTo(W-pad, H-pad); ctx.stroke();
  const maxVal = Math.max(1, ...series1, ...series2);
  function y(v){ return H - pad - (v/maxVal)*(H-2*pad); }
  function x(i){ const step=(W-2*pad)/(labels.length-1||1); return pad + step*i; }
  // grid
  ctx.strokeStyle="#e6f6ee"; ctx.lineWidth=1;
  for(let i=0;i<5;i++){ let yy=pad + i*(H-2*pad)/4; ctx.beginPath(); ctx.moveTo(pad,yy); ctx.lineTo(W-pad,yy); ctx.stroke(); }
  // labels
  ctx.fillStyle="#0f3d29"; ctx.font="12px Inter, system-ui";
  labels.forEach((lb,i)=>{ ctx.fillText(lb, x(i)-10, H-pad+16); });
  // series1 (green)
  ctx.strokeStyle="#1f9e63"; ctx.lineWidth=3; ctx.beginPath();
  series1.forEach((v,i)=>{ if(i===0) ctx.moveTo(x(i), y(v)); else ctx.lineTo(x(i), y(v)); }); ctx.stroke();
  // series2 (red)
  ctx.strokeStyle="#e64545"; ctx.lineWidth=3; ctx.beginPath();
  series2.forEach((v,i)=>{ if(i===0) ctx.moveTo(x(i), y(v)); else ctx.lineTo(x(i), y(v)); }); ctx.stroke();
}

drawLineChart('monthly',
  ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
  incomeMonthly, expenseMonthly);

drawLineChart('yearly', yearlyLabels, incomeYearly, expenseYearly);
</script>
</body>
</html>
