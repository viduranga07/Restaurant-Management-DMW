<?php
require_once 'config.php'; require_login();
$menuCount = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$revenue = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE order_status <> 'Cancelled'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status='Pending'")->fetchColumn();
$recent = $pdo->query("SELECT o.*,m.name item_name FROM orders o JOIN menu_items m ON m.id=o.menu_item_id ORDER BY o.id DESC LIMIT 6")->fetchAll();
include 'includes/header.php';
?>
<h1>Dashboard</h1>
<p class="muted">Live overview of restaurant operations.</p>
<div class="stats">
  <div class="stat"><span>Menu Items</span><strong><?= $menuCount ?></strong></div>
  <div class="stat"><span>Total Orders</span><strong><?= $orderCount ?></strong></div>
  <div class="stat"><span>Revenue</span><strong>Rs. <?= number_format($revenue,2) ?></strong></div>
  <div class="stat"><span>Pending</span><strong><?= $pending ?></strong></div>
</div>
<div class="grid-2">
<section class="panel">
<h2>Recent Orders</h2>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Customer</th><th>Item</th><th>Total</th><th>Status</th></tr></thead>
<tbody>
<?php foreach($recent as $r): ?>
<tr><td>#<?= e($r['id']) ?></td><td><?= e($r['customer_name']) ?></td><td><?= e($r['item_name']) ?></td><td>Rs. <?= number_format($r['total'],2) ?></td><td><span class="badge"><?= e($r['order_status']) ?></span></td></tr>
<?php endforeach; ?>
<?php if(!$recent): ?><tr><td colspan="5" class="muted">No orders yet.</td></tr><?php endif; ?>
</tbody></table></div>
</section>
<section class="panel">
<h2>Business Rule</h2>
<div class="rule"><b>Automatic Discount</b><br>Orders above Rs. 5,000 receive 10% discount.<br>Orders above Rs. 10,000 receive 15% discount.</div>
<h2>System Coverage</h2>
<ul class="clean"><li>PHP server validation</li><li>MySQL CRUD</li><li>AJAX order operations</li><li>Search & sorting</li><li>jQuery effects</li><li>CSV export</li><li>Password encryption</li><li>Dark mode</li></ul>
</section>
</div>
<?php include 'includes/footer.php'; ?>
