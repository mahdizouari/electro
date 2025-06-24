<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in
    $_SESSION['flash_message'] = "You must be logged in to add items to your wishlist.";
    header('Location: /electro'); // Redirect to login page
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_wishlist') {
    $id = (int)$_POST['product_id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $price = (float)$_POST['price'];
    $image = htmlspecialchars(trim($_POST['image']));

    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }

    if (!isset($_SESSION['wishlist'][$id])) {
        $_SESSION['wishlist'][$id] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'image' => $image
        ];
        $_SESSION['flash_message'] = "{$name} has been added to your wishlist.";
    } else {
        $_SESSION['flash_message'] = "{$name} is already in your wishlist.";
    }

    // After processing POST:
$_SESSION['flash_message'] = "Added to wishlist.";
$redirect_back = $_POST['redirect'] ?? '/electro';  // default fallback
header("Location: " . $redirect_back);
exit();

}
