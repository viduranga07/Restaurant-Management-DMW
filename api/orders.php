<?php
require_once '../config.php'; require_login();
header('Content-Type: application/json');

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? 'list';

    if ($action === 'list') {
        $q = trim($_GET['q'] ?? '');
        $sorts = ['id'=>'o.id','total'=>'o.total','customer_name'=>'o.customer_name'];
        $sort = $sorts[$_GET['sort'] ?? 'id'] ?? 'o.id';
        $stmt=$pdo->prepare("SELECT o.*,m.name item_name FROM orders o JOIN menu_items m ON m.id=o.menu_item_id
                             WHERE o.customer_name LIKE ? OR m.name LIKE ? ORDER BY $sort DESC");
        $like="%$q%"; $stmt->execute([$like,$like]);
        echo json_encode(['ok'=>true,'data'=>$stmt->fetchAll()]); exit;
    }

    if ($action === 'create') {
        $customer=trim($_POST['customer_name']??'');
        $itemId=(int)($_POST['menu_item_id']??0);
        $qty=(int)($_POST['quantity']??0);
        if ($customer==='' || $itemId<=0 || $qty<1 || $qty>100) throw new Exception('Invalid order details.');

        $pdo->beginTransaction();
        $stmt=$pdo->prepare("SELECT * FROM menu_items WHERE id=? AND status='Available' FOR UPDATE");
        $stmt->execute([$itemId]); $item=$stmt->fetch();
        if (!$item) throw new Exception('Menu item is unavailable.');
        if ($qty > $item['stock']) throw new Exception('Not enough stock available.');

        $subtotal=(float)$item['price']*$qty;
        $rate = $subtotal > 10000 ? 15 : ($subtotal > 5000 ? 10 : 0);
        $discount=$subtotal*$rate/100;
        $total=$subtotal-$discount;

        $stmt=$pdo->prepare("INSERT INTO orders(customer_name,menu_item_id,quantity,unit_price,subtotal,discount_rate,discount_amount,total) VALUES(?,?,?,?,?,?,?,?)");
        $stmt->execute([$customer,$itemId,$qty,$item['price'],$subtotal,$rate,$discount,$total]);
        $stmt=$pdo->prepare("UPDATE menu_items SET stock=stock-? WHERE id=?"); $stmt->execute([$qty,$itemId]);
        $pdo->commit();

        echo json_encode(['ok'=>true,'message'=>"Order created. Discount: {$rate}%",'total'=>$total]); exit;
    }

    if ($action === 'status') {
        $id=(int)($_POST['id']??0); $status=$_POST['status']??'';
        if (!in_array($status,['Pending','Preparing','Completed','Cancelled'],true)) throw new Exception('Invalid status.');
        $stmt=$pdo->prepare("UPDATE orders SET order_status=? WHERE id=?"); $stmt->execute([$status,$id]);
        echo json_encode(['ok'=>true,'message'=>'Order status updated.']); exit;
    }

    throw new Exception('Invalid request.');
} catch(Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['ok'=>false,'message'=>$e->getMessage()]);
}
