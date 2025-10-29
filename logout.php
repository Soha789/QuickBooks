<?php
session_start();
$_SESSION = [];
session_destroy();
echo "<script>alert('Logged out'); window.location.href='index.php';</script>";
