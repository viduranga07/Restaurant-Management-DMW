<?php
require_once '../config.php'; require_login();
header('Content-Type: application/json');

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? 'list';

    if ($action === 'list') {
        $q = trim($_GET['q'] ?? '');
        $sorts = ['name'=>'name','price'=>'price','stock'=>'stock'];
        $sort = $sorts[$_GET['sort'] ?? 'name'] ?? 'name';
        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE name LIKE ? OR category LIKE ? ORDER BY $sort ASC");
        $like = "%$q%"; $stmt->execute([$like,$like]);
        echo json_encode(['ok'=>true,'data'=>$stmt->fetchAll()]); exit;
    }

    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? -1);
        $status = $_POST['status'] ?? 'Available';

        if ($name === '' || $category === '' || $price <= 0 || $stock < 0 || !in_array($status,['Available','Unavailable'],true)) {
            throw new Exception('Please enter valid menu details.');
        }
        if ($id) {
            $stmt=$pdo->prepare("UPDATE menu_items SET name=?,category=?,price=?,stock=?,status=? WHERE id=?");
            $stmt->execute([$name,$category,$price,$stock,$status,$id]);
            echo json_encode(['ok'=>true,'message'=>'Menu item updated.']);
        } else {
            $stmt=$pdo->prepare("INSERT INTO menu_items(name,category,price,stock,status) VALUES(?,?,?,?,?)");
            $stmt->execute([$name,$category,$price,$stock,$status]);
            echo json_encode(['ok'=>true,'message'=>'Menu item created.']);
        }
        exit;
    }

    if ($action === 'delete') {
        $id=(int)($_POST['id']??0);
        $stmt=$pdo->prepare("SELECT COUNT(*) FROM orders WHERE menu_item_id=?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) throw new Exception('Cannot delete an item already used in orders.');
        $stmt=$pdo->prepare("DELETE FROM menu_items WHERE id=?"); $stmt->execute([$id]);
        echo json_encode(['ok'=>true,'message'=>'Menu item deleted.']); exit;
    }

    throw new Exception('Invalid request.');
} catch(Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'message'=>$e->getMessage()]);
}
