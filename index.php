<?php
session_start();
if(!isset($_SESSION['app'])) {
  $_SESSION['app'] = [
    'user' => null,
    'invoices' => [],
    'expenses' => [],
    'transactions' => [] // {type:'Receive'|'Send', amount, note, date}
  ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Finovo — Simple Accounting</title>
<style>
:root{
  --mint:#dffbe9; --mint2:#c9f4db; --green:#1f9e63; --green-d:#147a4b; --white:#ffffff; --ink:#1b2b21;
}
*{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial;
background:linear-gradient(180deg,var(--mint),var(--white) 65%); color:var(--ink);}
.container{max-width:1100px;margin:0 auto;padding:24px}
.nav{display:flex;justify-content:space-between;align-items:center;background:var(--white);
border:1px solid #e6f6ee;border-radius:16px;padding:14px 18px;box-shadow:0 10px 30px rgba(31,158,99,.08)}
.brand{display:flex;gap:10px;align-items:center;font-weight:800;color:var(--green)}
.logo{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--green),#38d39f)}
.cta a{display:inline-block;padding:10px 16px;border-radius:10px;text-decoration:none;font-weight:700}
.btn-outline{border:2px solid var(--green);color:var(--green);background:transparent}
.btn-solid{background:var(--green);color:var(--white);margin-left:8px}
.hero{display:grid;grid-template-columns:1.1fr 0.9fr;gap:24px;align-items:center;margin-top:32px}
.card{background:var(--white);border:1px solid #e6f6ee;border-radius:18px;padding:24px;box-shadow:0 14px 40px rgba(20,122,75,.10)}
.h-title{font-size:40px;line-height:1.1;margin:0 0 8px}
.sub{opacity:.8}
.features{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-top:16px}
.tag{background:var(--mint2);color:#0d5232;padding:8px 12px;border-radius:999px;font-weight:700;display:inline-block}
.center-panel{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%}
.big-cta{margin-top:12px;display:flex;gap:12px}
.footer{margin-top:36px;text-align:center;opacity:.7}
</style>
</head>
<body>
  <div class="container">
    <div class="nav">
      <div class="brand">
        <div class="logo"></div> Finovo
      </div>
      <div class="cta">
        <a href="#" class="btn-outline" onclick="go('signup.php')">Sign up</a>
        <a href="#" class="btn-solid" onclick="go('login.php')">Log in</a>
      </div>
    </div>

    <div class="hero">
      <div class="card">
        <h1 class="h-title">Manage invoices, expenses, and reports — simply.</h1>
        <p class="sub">A lightweight, QuickBooks-style experience: create and track invoices, log expenses
        and income, see a live dashboard, and view monthly/yearly insights with crisp line graphs.</p>
        <div class="features">
          <span class="tag">Invoices & PDF Print</span>
          <span class="tag">Expenses & Categories</span>
          <span class="tag">Income & Balance</span>
          <span class="tag">Monthly / Yearly Charts</span>
          <span class="tag">JS Redirects</span>
          <span class="tag">Responsive UI</span>
        </div>
      </div>

      <div class="card center-panel">
        <h2 style="margin:0 0 6px">Get started</h2>
        <p class="sub" style="text-align:center">Create your account or log in to access your dashboard.</p>
        <div class="big-cta">
          <a href="#" class="btn-outline" onclick="go('signup.php')">Create account</a>
          <a href="#" class="btn-solid" onclick="go('login.php')">Access dashboard</a>
        </div>
      </div>
    </div>

    <p class="footer">Made with ☘️ — Light green & white theme throughout</p>
  </div>

<script>
function go(where){ window.location.href = where; }
</script>
</body>
</html>
