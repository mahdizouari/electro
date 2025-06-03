<!DOCTYPE html>
<html lang="en">
	<head>
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
<body>

	<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/header.php'); ?>
   
  <div class="cart-page mt-5 mb-5">
    <h4>Shopping cart</h4>
    <?php
        session_start();        
      
        // Handle form submissions for updating quantity or removing item
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && isset($_POST['product_id'])) {
                $pid = (int)$_POST['product_id'];

                if ($pid && isset($_SESSION['cart'][$pid])) {
                    if ($_POST['action'] === 'increase') {
                        $_SESSION['cart'][$pid]['quantity']++;
                    } elseif ($_POST['action'] === 'decrease') {
                        $_SESSION['cart'][$pid]['quantity']--;
                        if ($_SESSION['cart'][$pid]['quantity'] <= 0) {
                            unset($_SESSION['cart'][$pid]);
                        }
                    } elseif ($_POST['action'] === 'remove') {
                        unset($_SESSION['cart'][$pid]);
                    }
                }
            }
           
        }

        // Calculate total
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
    ?>
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-message" style="padding: 10px; background-color: #d1ecf1; color: #0c5460; margin-bottom: 15px; border-radius: 5px;">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message']); // Remove after showing ?>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>
      <p>Your cart is empty.</p>
    <?php else: ?>
        <?php
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $product_id => $item):
            $productName = htmlspecialchars($item['name']);
            $productImage = htmlspecialchars($item['image']);
            $quantity = (int) $item['quantity'];
            $price = (float) $item['price'];
            $subtotal = $price * $quantity;

            $totalPrice += $subtotal;
        ?>
        <div class="product-card">
            <!-- Product Image -->
            <img src="<?= $productImage ?>" alt="<?= $productName ?>" />

            <!-- Product Info -->
            <div class="product-details">
            <span class="font-weight-bold"><?= $productName ?></span>
            <div class="product-desc">
                
            </div>
            </div>

            <!-- Quantity Controls -->
            <div class="qty">
            <form method="post" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="action" value="decrease">
                <button type="submit" class="fa fa-minus text-danger" aria-label="Decrease quantity"></button>
            </form>

            <h5 class="text-grey"><?= $quantity ?></h5>

            <form method="post" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="action" value="increase">
                <button type="submit" class="fa fa-plus text-success" aria-label="Increase quantity"></button>
            </form>
            </div>

            <!-- Subtotal Price -->
            <div>
            <h5 class="text-grey"><?= number_format($subtotal, 2) ?> TND</h5>
            </div>

            <!-- Remove Button -->
            <div class="d-flex align-items-center">
            <form method="post" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="action" value="remove">
                <button type="submit" class="fa fa-trash" aria-label="Remove item"></button>
            </form>
            </div>
        </div>
        <?php endforeach; ?>

     

      <!-- Total and Pay -->
      <div class="mt-3">
        <h4>Total: <?= number_format($totalPrice, 2) ?> TND</h4>
        <form action="/electro/pages/checkout.php" method="get">
            <button class="pay-button" type="submit">Proceed to Pay</button>
        </form>
    </div>

    <?php endif; ?>
  </div>

    
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/footer.php'); ?>
<style>
    .cart-page {
  max-width: 950px;
  margin: 40px auto;
  font-family: 'Manrope', sans-serif;
  color: #333;
}

.cart-page h4 {
  margin-bottom: 20px;
  font-weight: bold;
  color: #d32f2f;
}

.cart-page .sorting {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  font-size: 14px;
  margin-bottom: 15px;
  color: #555;
}

.cart-page .sorting i {
  margin-left: 5px;
  cursor: pointer;
}

.cart-page .product-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fff;
  border: 1px solid #eee;
  padding: 16px;
  border-radius: 10px;
  margin-bottom: 20px;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.06);
}

.cart-page .product-card img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #ddd;
}

.cart-page .product-details {
  flex: 1;
  margin-left: 20px;
}

.cart-page .product-details span {
  display: block;
  font-weight: 600;
  font-size: 15px;
  margin-bottom: 5px;
}

.cart-page .product-desc {
  font-size: 13px;
  color: #777;
}

.cart-page .qty {
  display: flex;
  align-items: center;
  gap: 8px;
}

.cart-page .qty button {
  background: none;
  border: none;
  font-size: 16px;
  padding: 4px 8px;
  color: #d32f2f;
  cursor: pointer;
  border-radius: 4px;
}

.cart-page .qty button:hover {
  background-color: #fce4e4;
}

.cart-page .qty h5 {
  margin: 0;
  font-size: 15px;
  min-width: 24px;
  text-align: center;
  color: #444;
}

.cart-page .price {
  font-size: 15px;
  font-weight: 600;
  color: #222;
}

.cart-page .fa-trash {
  color: #d32f2f;
  font-size: 18px;
  cursor: pointer;
  transition: color 0.2s ease;
}

.cart-page .fa-trash:hover {
  color: #b30000;
}

.cart-page .total {
  text-align: right;
  margin-top: 30px;
}

.cart-page .total h4 {
  color: #000;
  font-size: 18px;
}

.cart-page .pay-button {
  margin-top: 15px;
  padding: 10px;
  width: 100%;
  background-color: #d32f2f;
  border: none;
  color: white;
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  transition: background-color 0.3s;
}

.cart-page .pay-button:hover {
  background-color: #b71c1c;
}

@media (max-width: 576px) {
  .cart-page .product-card {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px;
  }

  .cart-page .product-details {
    margin-left: 0;
    margin-top: 10px;
  }

  .cart-page .qty {
    justify-content: center;
  }

  .cart-page .total {
    text-align: center;
  }
}

</style>
</body>
</html>
