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

    </head>
	<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/header.php'); ?>


		

		
            <!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title"> All Products</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
									<li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
									<li><a data-toggle="tab" href="#tab1">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab1">Accessories</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /section title -->
                    
					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<?php
							require_once '/opt/lampp/htdocs/electro/pages/includes/pdo.php';
							$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
							$products = $stmt->fetchAll();

							foreach ($products as $product):
							?>
							<!-- product -->
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="product">
									<div class="product-img">
										<img src="/electro/<?= htmlspecialchars($product['image'] ?: 'img/default-product.png') ?>" alt="" style="max-height:200px; object-fit:contain;">
										<div class="product-label">
											<?php if (!empty($product['discount'])): ?>
												<span class="sale">-<?= (int)$product['discount'] ?>%</span>
											<?php endif; ?>
											<?php if (!empty($product['is_new'])): ?>
												<span class="new">NEW</span>
											<?php endif; ?>
										</div>
									</div>
									<div class="product-body">
										<p class="product-category"><?= htmlspecialchars($product['category'] ?? 'Other') ?></p>
										<h3 class="product-name">
											<a href="/electro/pages/product_detail.php?id=<?= $product['id'] ?>">
												<?= htmlspecialchars($product['name']) ?>
											</a>
										</h3>
										<h4 class="product-price">
											<?= number_format($product['price'], 2) ?> TND
											<?php if (!empty($product['old_price'])): ?>
												<del class="product-old-price"><?= number_format($product['old_price'], 2) ?> TND</del>
											<?php endif; ?>
										</h4>
										<div class="product-rating">
											<?php
											$rating = (int)($product['rating'] ?? 0);
											for ($i = 1; $i <= 5; $i++):
												echo '<i class="fa fa-star' . ($i <= $rating ? '' : '-o') . '"></i>';
											endfor;
											?>
										</div>
										<div class="product-btns">
											<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
											<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
											<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
										</div>
									</div>
									<div class="add-to-cart">
										<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
									</div>
								</div>
							</div>
							<!-- /product -->
							<?php endforeach; ?>
						</div>
					</div>


					<!-- Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
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
