<?php
session_start();

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
    }
}

$_SESSION['flash_message'] = "{$name} has been added to your wishlist.";

// Redirect to the wishlist page so user sees the message
header('Location: /electro');