<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = (int) ($_POST['product_id'] ?? 0);

    if ($pid && isset($_SESSION['cart'][$pid])) {
        if ($_POST['action'] === 'remove') {
            unset($_SESSION['cart'][$pid]);
            $_SESSION['flash_message'] = 'Item removed from cart.';
        } elseif ($_POST['action'] === 'add_to_cart') {
            $item = $_SESSION['cart'][$pid];

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$pid])) {
                $_SESSION['cart'][$pid]['quantity']++;
            } else {
                $_SESSION['cart'][$pid] = $item;
                $_SESSION['cart'][$pid]['quantity'] = 1;
            }

            unset($_SESSION['cart'][$pid]);
            $_SESSION['flash_message'] = 'Item moved to cart.';
        }
    }
    $_SESSION['flash_message'] = "Removed From Cart";

    header('location: cart.php');
    exit;
}
?>
