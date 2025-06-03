<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wishlist - Electro</title>
    <link rel="stylesheet" href="/electro/css/bootstrap.min.css">
    <link rel="stylesheet" href="/electro/css/font-awesome.min.css">
    <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		 <!-- The above 3 meta tags *must* come * in the head; any other head content must come *after* these tags -->

		<title>Electro Shop</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="css/slick.css"/>
		<link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="css/font-awesome.min.css">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="css/style.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    <style>
        .wishlist-page {
            max-width: 950px;
            margin: 40px auto;
            font-family: 'Manrope', sans-serif;
        }
        .wishlist-page h4 {
            margin-bottom: 20px;
            color: #d32f2f;
            font-weight: bold;
        }
        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #eee;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .product-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-details {
            flex: 1;
            margin-left: 20px;
        }
        .wishlist-actions form {
            display: inline;
        }
        .wishlist-actions button {
            margin-right: 10px;
            background-color: #d32f2f;
            border: none;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .wishlist-actions button:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/header.php'); ?>

<div class="wishlist-page">
    <h4>Your Wishlist</h4>

    <?php if (empty($_SESSION['wishlist'])): ?>
        <p>Your wishlist is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['wishlist'] as $product_id => $item): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="product-details">
                    <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                    <span><?= number_format($item['price'], 2) ?> TND</span>
                </div>
                <div class="wishlist-actions">
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="action" value="add_to_cart">
                        <button type="submit">Add to Cart</button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" class="btn-remove">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
session_start();

// Handle remove or add-to-cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = (int) $_POST['product_id'] ?? 0;

    if ($pid && isset($_SESSION['wishlist'][$pid])) {
        if ($_POST['action'] === 'remove') {
            unset($_SESSION['wishlist'][$pid]);
        } elseif ($_POST['action'] === 'add_to_cart') {
            // Add to cart and remove from wishlist
            $item = $_SESSION['wishlist'][$pid];
            if (isset($_SESSION['cart'][$pid])) {
                $_SESSION['cart'][$pid]['quantity']++;
            } else {
                $_SESSION['cart'][$pid] = $item;
                $_SESSION['cart'][$pid]['quantity'] = 1;
            }
            unset($_SESSION['wishlist'][$pid]);
        }
    }
}
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/footer.php'); ?>
</body>
</html>
