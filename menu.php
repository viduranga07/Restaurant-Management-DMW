<?php
require_once 'config.php'; require_login();
include 'includes/header.php';
?>
<div class="page-head"><div><h1>Menu Management</h1><p class="muted">Create, read, update and delete menu items.</p></div><button class="btn primary" id="openMenuModal">+ Add Menu Item</button></div>
<div class="toolbar">
<input id="menuSearch" type="search" placeholder="Search menu items...">
<select id="menuSort"><option value="name">Sort by name</option><option value="price">Sort by price</option><option value="stock">Sort by stock</option></select>
</div>
<div id="menuMsg" aria-live="polite"></div>
<div class="panel"><div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
<tbody id="menuBody"></tbody></table></div></div>

<div class="modal" id="menuModal"><div class="modal-card">
<button class="close" id="closeMenuModal">×</button><h2 id="modalTitle">Add Menu Item</h2>
<form id="menuForm">
<input type="hidden" name="id" id="menuId">
<label>Item Name</label><input name="name" id="menuName" required maxlength="100">
<label>Category</label><select name="category" id="menuCategory" required><option>Fried Rice</option><option>Kottu</option><option>Biriyani</option><option>Burger</option><option>Mongolian</option><option>Thai</option><option>Rice</option></select>
<label>Price</label><input name="price" id="menuPrice" type="number" min="1" step="0.01" required>
<label>Stock</label><input name="stock" id="menuStock" type="number" min="0" required>
<label>Status</label><select name="status" id="menuStatus"><option>Available</option><option>Unavailable</option></select>
<button class="btn primary full">Save Item</button>
</form></div></div>
<?php include 'includes/footer.php'; ?>
