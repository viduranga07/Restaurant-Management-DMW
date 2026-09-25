<?php
require_once 'config.php'; require_login();
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="restaurant_orders.csv"');
$out=fopen('php://output','w');
fputcsv($out,['Order ID','Customer','Menu Item','Quantity','Subtotal','Discount Rate','Discount Amount','Total','Status','Created At']);
$stmt=$pdo->query("SELECT o.*,m.name item_name FROM orders o JOIN menu_items m ON m.id=o.menu_item_id ORDER BY o.id DESC");
while($r=$stmt->fetch()) {
    fputcsv($out,[$r['id'],$r['customer_name'],$r['item_name'],$r['quantity'],$r['subtotal'],$r['discount_rate'].'%',$r['discount_amount'],$r['total'],$r['order_status'],$r['created_at']]);
}
exit;
