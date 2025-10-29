<?php
session_start();
if(!isset($_SESSION['app']['user'])){ echo "<script>window.location.href='login.php';</script>"; exit; }
$app =& $_SESSION['app'];
$invoices =& $app['invoices'];

// Mark paid via GET (JS redirect back after)
if(isset($_GET['markpaid'])){
  $id = $_GET['markpaid'];
  foreach($invoices as &$inv){ if($inv['id']==$id){ $inv['status']='Paid'; break; } }
  echo "<script>alert('Invoice marked as Paid'); window.location.href='invoices.php';</script>"; exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoices — Finovo</title>
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
.btn{padding:6px 10px;border-radius:10px;font-weight:800;border:none;cursor:pointer}
.btn-g{background:var(--green);color:var(--white)}
.btn-o{background:#c9f4db}
.head{display:flex;justify-content:space-between;align-items:center}
</style>
</head>
<body>
<div class="container">
  <div class="nav">
    <div style="font-weight:900;color:var(--green)">Finovo</div>
    <div>
      <a href="dashboard.php">Dashboard</a>
      <a class="active" href="invoices.php">Invoices</a>
      <a href="expenses.php">Expenses</a>
      <a href="reports.php">Reports</a>
      <a href="transactions.php">Transactions</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="card">
    <div class="head">
      <h2 style="margin:0">Invoices</h2>
      <button class="btn btn-g" onclick="go('create_invoice.php')">Create Invoice</button>
    </div>
    <table class="table">
      <tr><th>#</th><th>Client</th><th>Date</th><th>Amount (SAR)</th><th>Status</th><th>Actions</th></tr>
      <?php if(empty($invoices)): ?>
        <tr><td colspan="6">No invoices yet.</td></tr>
      <?php else: foreach($invoices as $inv): ?>
        <tr>
          <td><?php echo htmlspecialchars($inv['id']);?></td>
          <td><?php echo htmlspecialchars($inv['client']);?></td>
          <td><?php echo htmlspecialchars($inv['date']);?></td>
          <td><?php echo number_format($inv['amount'],2);?></td>
          <td><span class="btn-o" style="padding:4px 8px;border-radius:999px"><?php echo htmlspecialchars($inv['status']);?></span></td>
          <td>
            <button class="btn" onclick="markPaid('<?php echo $inv['id'];?>')">Mark Paid</button>
            <button class="btn" onclick="viewInvoice('<?php echo $inv['id'];?>')">View/Print</button>
            <button class="btn" onclick="emailInvoice('<?php echo rawurlencode($inv['client']);?>','<?php echo rawurlencode($inv['id']);?>','<?php echo number_format($inv['amount'],2);?>')">Email</button>
          </td>
        </tr>
      <?php endforeach; endif;?>
    </table>
  </div>
</div>
<script>
function go(w){ window.location.href=w; }
function markPaid(id){ window.location.href='invoices.php?markpaid='+encodeURIComponent(id); }
function viewInvoice(id){ 
  const w = window.open('', '_blank');
  w.document.write(`<html><head><title>Invoice ${id}</title>
  <style>
  body{font-family:Inter,system-ui;padding:24px;background:#dffbe9}
  .sheet{background:#fff;border:1px solid #e6f6ee;border-radius:16px;padding:20px}
  h2{color:#1f9e63;margin:0 0 6px}
  </style></head><body>
  <div class="sheet"><h2>Invoice #${id}</h2><p>Print this page to save as PDF.</p></div>
  <script>setTimeout(()=>window.print(),300);<\/script></body></html>`);
}
function emailInvoice(client,id,amount){
  const subject = `Invoice #${id}`;
  const body = `Dear ${decodeURIComponent(client)},%0D%0A%0D%0APlease find your invoice #%23${id} for SAR ${amount}.%0D%0AThanks!`;
  window.location.href = `mailto:?subject=${subject}&body=${body}`;
}
</script>
</body>
</html>
