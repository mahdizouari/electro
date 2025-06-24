<?php
session_start();
require_once '/opt/lampp/htdocs/electro/pages/includes/pdo.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = 'You must be logged in to add items to your cart.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$product_id = $_POST['product_id'] ?? null;

if ($product_id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $id = $product['id'];

        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        } else {
            $_SESSION['cart'][$id]['quantity']++;
        }

        $_SESSION['flash_message'] = "{$product['name']} has been added to your cart.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        $_SESSION['flash_message'] = 'Product not found.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

$_SESSION['flash_message'] = 'Invalid product ID.';
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
