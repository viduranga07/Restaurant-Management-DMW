<?php
require_once 'config.php'; require_login();
$items = $pdo->query("SELECT * FROM menu_items WHERE status='Available' AND stock>0 ORDER BY name")->fetchAll();
include 'includes/header.php';
?>
<div class="page-head"><div><h1>Order Management</h1><p class="muted">Create and manage customer orders.</p></div><button class="btn primary" id="openOrderModal">+ New Order</button></div>
<div class="toolbar">
<input id="orderSearch" type="search" placeholder="Search customer or item...">
<select id="orderSort"><option value="id">Newest</option><option value="total">Total</option><option value="customer_name">Customer</option></select>
</div>
<div id="orderMsg" aria-live="polite"></div>
<div class="panel"><div class="table-wrap"><table><thead><tr><th>ID</th><th>Customer</th><th>Item</th><th>Qty</th><th>Subtotal</th><th>Discount</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
<tbody id="orderBody"></tbody></table></div></div>

<div class="modal" id="orderModal"><div class="modal-card">
<button class="close" id="closeOrderModal">×</button><h2>New Order</h2>
<form id="orderForm">
<label>Customer Name</label><input name="customer_name" required maxlength="100">
<label>Menu Item</label><select name="menu_item_id" id="orderItem" required><option value="">Select item</option>
<?php foreach($items as $i): ?><option value="<?= $i['id'] ?>" data-price="<?= $i['price'] ?>"><?= e($i['name']) ?> — Rs. <?= number_format($i['price'],2) ?> (<?= $i['stock'] ?> left)</option><?php endforeach; ?>
</select>
<label>Quantity</label><input name="quantity" id="orderQty" type="number" min="1" max="100" value="1" required>
<div class="preview" id="orderPreview">Total: Rs. 0.00</div>
<button class="btn primary full">Place Order</button>
</form></div></div>
<?php include 'includes/footer.php'; ?>
