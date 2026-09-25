<?php require_once __DIR__ . '/../config.php'; require_login(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GastroNova RMS</title>
<link rel="stylesheet" href="assets/css/style.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<header class="topbar">
  <div class="brand">GASTRONOVA <span>RMS</span></div>
  <div class="top-actions">
    <button id="darkMode" class="icon-btn" title="Toggle dark mode">◐</button>
    <span><?= e($_SESSION['user']['name']) ?> · <?= e($_SESSION['user']['role']) ?></span>
    <a href="logout.php" class="logout">Logout</a>
  </div>
</header>
<div class="layout">
<aside class="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="menu.php">Menu Management</a>
  <a href="orders.php">Order Management</a>
  <a href="export.php">Export CSV</a>
</aside>
<main class="content">
