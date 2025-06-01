

<!-- HEADER -->
<header>
    
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href=""><i class="fa fa-phone"></i> +21695419210</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> ElectroShop@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> Cité Ons</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-dollar"></i> TND</a></li>

						<?php
						session_start();

						if (isset($_SESSION['user_id'])) {
							// User is logged in
							if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
								// Admin logged in: show My Account and Logout only
								echo '<li><a href="/electro/pages/dashboard.php"><i class="fa fa-user-o"></i> My Account</a></li>';
								echo '<li><a href="/electro/pages/logout.php"><i class="fa fa-user-o"></i> Logout</a></li>';
							} else {
								// Normal user logged in: show only Logout
								echo '<li><a href="/electro/pages/logout.php"><i class="fa fa-user-o"></i> Logout</a></li>';
							}
						} else {
							// Not logged in: show Login and Register links
							echo '<li><a href="/electro/pages/login.php"><i class="fa fa-user-o"></i> Log in</a></li>';
							echo '<li><a href="/electro/pages/register.php"><i class="fa fa-user-o"></i> Register</a></li>';
						}
						?>
					</ul>

				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="/electro" class="logo">
									<img src="./img/logo.png" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							<div class="header-search">
								<form>
									<select class="input-select">
										<option value="0">All Categories</option>
										<option value="1">Category 01</option>
										<option value="1">Category 02</option>
									</select>
									<input class="input" placeholder="Search here">
									<button class="search-btn">Search</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Wishlist -->
								<div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qty">2</div>
									</a>
								</div>
								<!-- /Wishlist -->

								<!-- Cart -->
								<div class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										<div class="qty">3</div>
									</a>
									<div class="cart-dropdown">
										<div class="cart-list">
											<div class="product-widget">
												<div class="product-img">
													<img src="./img/product01.png" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">product name goes here</a></h3>
													<h4 class="product-price"><span class="qty">1x</span>$980.00</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>

											<div class="product-widget">
												<div class="product-img">
													<img src="./img/product02.png" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">product name goes here</a></h3>
													<h4 class="product-price"><span class="qty">3x</span>$980.00</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>
										</div>
										<div class="cart-summary">
											<small>3 Item(s) selected</small>
											<h5>SUBTOTAL: $2940.00</h5>
										</div>
										<div class="cart-btns">
											<a href="#">View Cart</a>
											<a href="#">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
             <!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav">
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><a href="/electro">Home</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'allproducts.php' ? 'active' : '' ?>"><a href="/electro/pages/allproducts.php">Products</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>"><a href="/electro/pages/checkout.php">Cart</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'laptop.php' ? 'active' : '' ?>"><a href="/electro/pages/laptop.php">Laptop</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'cameras.php' ? 'active' : '' ?>"><a href="/electro/pages/cameras.php">Cameras</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'accessories.php' ? 'active' : '' ?>"><a href="/electro/pages/accessories.php">Accessories</a></li>
						</ul>

					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->
		</header>
		<!-- /HEADER -->