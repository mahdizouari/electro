<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Electro - HTML Ecommerce Template</title>

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

    </head>
	<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/header.php'); ?>
	

		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<h3 class="breadcrumb-header">Checkout</h3>
						<ul class="breadcrumb-tree">
							<li><a href="/electro">Home</a></li>
							<li class="active">Checkout</li>
						</ul>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /BREADCRUMB -->

		<!-- SECTION -->
		

<form method="POST">
	<div class="section">
		<div class="container">
			<div class="row">
				<!-- Order Details -->
				<div class="col-md-5 order-details">
					<div class="section-title text-center">
						<h3 class="title">Your Order</h3>
					</div>
					<div class="order-summary">
						<div class="order-col">
							<div><strong>PRODUCT</strong></div>
							<div><strong>TOTAL</strong></div>
						</div>
						<div class="order-products">
							<?php
							$total = 0;
							if (!empty($_SESSION['cart'])):
								foreach ($_SESSION['cart'] as $item):
									$itemTotal = $item['price'] * $item['quantity'];
									$total += $itemTotal;
							?>
								<div class="order-col">
									<div><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?></div>
									<div><?= number_format($itemTotal, 2) ?> DT</div>
								</div>
							<?php endforeach; else: ?>
								<div class="order-col">
									<div>No items in cart</div>
									<div>0 DT</div>
								</div>
							<?php endif; ?>
						</div>
						<div class="order-col">
							<div>Shipping</div>
							<div><strong><?= $shippingFee ?> 8DT</strong></div>
						</div>
						<div class="order-col">
							<div><strong>TOTAL</strong></div>
							<div><strong class="order-total"><?= number_format($total + $shippingFee +8, 2) ?> DT</strong></div>
						</div>
					</div>
					<a href="#" onclick="this.closest('form').submit()" class="primary-btn order-submit">Place order</a>
				</div>

				<!-- Billing Form -->
				<div class="col-md-7">
				<div class="billing-details">
					<div class="section-title">
						<h3 class="title">Billing address</h3>
					</div>
					<div class="form-group">
						<input class="input" type="text" name="firstname" placeholder="First Name" required>
					</div>
					<div class="form-group">
						<input class="input" type="text" name="lastname" placeholder="Last Name" required>
					</div>
					<div class="form-group">
						<input class="input" type="text" name="address" placeholder="Address" required>
					</div>
					<div class="form-group">
						<input class="input" type="text" name="city" placeholder="City" required>
					</div>
					<div class="form-group">
						<input class="input" type="tel" name="tel" placeholder="Telephone" required>
					</div>
				</div>
				<?php
					session_start();
					require_once '/opt/lampp/htdocs/electro/pages/includes/pdo.php';

					if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						// Validate required fields
						if (
							isset($_POST['firstname'], $_POST['lastname'], $_POST['address'], $_POST['city'], $_POST['tel']) &&
							!empty($_POST['firstname']) && !empty($_POST['lastname']) &&
							!empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['tel']) &&
							isset($_SESSION['cart']) && !empty($_SESSION['cart'])
						) {
							$firstname = $_POST['firstname'];
							$lastname = $_POST['lastname'];
							$address = $_POST['address'];
							$city = $_POST['city'];
							$tel = $_POST['tel'];
							$date = date('Y-m-d H:i:s');

							foreach ($_SESSION['cart'] as $item) {
								$product_id = $item['id'];
								$quantity = isset($item['quantity']) ? $item['quantity'] : 1;
								$name = $firstname . ' ' . $lastname;

								// Insert into cart table
								$stmt = $pdo->prepare("INSERT INTO cart (product_id, quantity, name, lastname, adress, city, numero, date)
													VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
								$stmt->execute([$product_id, $quantity, $firstname, $lastname, $address, $city, $tel, $date]);
							}

							// Clear the cart session
							unset($_SESSION['cart']);

							// Display blank success message page
							echo "<!DOCTYPE html>
							<html>
							<head><title>Order Confirmation</title></head>
							<body style='text-align:center; font-family:Arial; margin-top:50px;'>
								<h2>✅ Order placed successfully!</h2>
								<p>Thank you for your purchase.</p>
							</body>
							</html>";
							exit;
						} else {
							echo "Missing required fields or cart is empty.";
						}
					} else {
						echo "Invalid request.";
					}
					?>	



					<?php if ($successMsg): ?>
						<div style="padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-top: 15px;">
							<?= $successMsg ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</form>

		<!-- /SECTION -->

	<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/footer.php'); ?>
		

		<!-- jQuery Plugins -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>
