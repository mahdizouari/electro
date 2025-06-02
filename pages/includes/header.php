

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
  <div class="header-search-container" style="position: relative;">
    <div class="header-search" id="search-area">
      <form method="GET" action="">
        <select class="input-select" name="category">
          <option value="">All Categories</option>
          <option value="Laptops">Laptops</option>
          <option value="Cameras">Cameras</option>
          <option value="Smartphones">Smartphones</option>
          <option value="Accessories">Accessories</option>
        </select>
        <input class="input" name="search" id="search-input" placeholder="Search here" autocomplete="off"
          value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="search-btn">Search</button>
      </form>
    </div>

    <!-- Search Result Box -->
    <div id="search-results" class="search-results-box" style="display: none;">
      <?php
        $conn = new mysqli("localhost", "root", "", "electro");
        if ($conn->connect_error) die("DB error: " . $conn->connect_error);

        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';

        $sql = "SELECT name, description, price, image FROM products WHERE 1";
        $params = [];
        $types = "";

        if ($searchTerm !== '') {
            $sql .= " AND name LIKE ?";
            $params[] = '%' . $searchTerm . '%';
            $types .= "s";
        }

        if ($category !== '') {
            $sql .= " AND category = ?";
            $params[] = $category;
            $types .= "s";
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($searchTerm !== '') {
          if ($result->num_rows > 0):
            while ($product = $result->fetch_assoc()):
              $filename = basename($product['image']);
              $imagePath = "uploads/" . $filename;
              $imageURL = htmlspecialchars($imagePath);
              $showImage = file_exists($imagePath) && !is_dir($imagePath);
      ?>
        <div style="display: flex; gap: 10px; align-items: center; padding: 8px 5px; border-bottom: 1px solid #eee;">
          <?php if ($showImage): ?>
            <img src="<?= $imageURL ?>" alt="Product Image"
              style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
          <?php endif; ?>
          <div>
            <strong><?= htmlspecialchars($product['name']) ?></strong><br>
            <small><?= htmlspecialchars($product['description']) ?></small><br>
            <span><strong>$<?= number_format($product['price'], 2) ?></strong></span>
          </div>
        </div>
      <?php endwhile; else: ?>
        <p style="margin: 0;">No products available at the moment.</p>
      <?php endif; } ?>
    </div>
  </div>
</div>

<!-- CSS -->
<style>
  #search-results {
    display: none;
    background: #fff;
    border: 1px solid #ccc;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    padding: 10px;
    z-index: 999;
    max-height: 300px;
    overflow-y: auto;
    border-radius: 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
    font-size: 14px;
  }

  #search-results>div:hover {
    background-color: #f0f0f0;
  }

  #search-results p {
    margin: 0;
    color: #888;
  }
</style>

<!-- JavaScript -->
<script>
  (function () {
    const searchArea = document.querySelector('.header-search-container');
    const searchResults = document.getElementById('search-results');
    const input = document.getElementById('search-input');
    const categorySelect = searchArea.querySelector('select[name="category"]');
    let timeoutId = null;

    function fetchResults() {
      const searchTerm = input.value.trim();
      const category = categorySelect.value;

      if (searchTerm === '') {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
      }

      const url = new URL(window.location.href);
      url.searchParams.set('search', searchTerm);
      url.searchParams.set('category', category);

      fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newResults = doc.getElementById('search-results');
          if (newResults && newResults.innerHTML.trim() !== '') {
            searchResults.innerHTML = newResults.innerHTML;
            searchResults.style.display = 'block';
          } else {
            searchResults.innerHTML = '<p>No products available at the moment.</p>';
            searchResults.style.display = 'block';
          }
        })
        .catch(() => {
          searchResults.innerHTML = '<p>Error loading results.</p>';
          searchResults.style.display = 'block';
        });
    }

    input.addEventListener('input', () => {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(fetchResults, 300);
    });

    categorySelect.addEventListener('change', fetchResults);

    searchArea.addEventListener('mouseenter', () => {
      if (searchResults.innerHTML.trim() !== '') {
        searchResults.style.display = 'block';
      }
    });

    searchArea.addEventListener('mouseleave', () => {
      searchResults.style.display = 'none';
    });

    input.addEventListener('focus', () => {
      if (searchResults.innerHTML.trim() !== '') {
        searchResults.style.display = 'block';
      }
    });

    input.addEventListener('blur', () => {
      setTimeout(() => {
        if (!searchArea.matches(':hover')) {
          searchResults.style.display = 'none';
        }
      }, 200);
    });

    if (input.value.trim() !== '') {
      fetchResults();
    }
  })();
</script>
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

								<?php
									session_start();
									$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
									$totalQuantity = 0;
									$totalPrice = 0.0;
									?>

									<!-- Cart -->
									<div class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										<div class="qty"><?= count($cart) ?></div>
									</a>
									<div class="cart-dropdown">
										<div class="cart-list">
										<?php if (empty($cart)): ?>
											<p class="text-center">Cart is empty</p>
										<?php else: ?>
											<?php foreach ($cart as $product): 
											$productName = htmlspecialchars($product['name']);
											$productImage = htmlspecialchars($product['image']);
											$price = (float) $product['price'];
											$quantity = (int) $product['quantity'];
											$subtotal = $price * $quantity;
											$totalQuantity += $quantity;
											$totalPrice += $subtotal;
											?>
											<div class="product-widget">
												<div class="product-img">
												<img src="<?= $productImage ?>" alt="<?= $productName ?>">
												</div>
												<div class="product-body">
												<h3 class="product-name"><a href="#"><?= $productName ?></a></h3>
												<h4 class="product-price"><span class="qty"><?= $quantity ?>x</span><?= number_format($price, 2) ?> TND</h4>
												</div>
												<form method="post">
												<input type="hidden" name="product_id" value="<?= $product['id'] ?>">
												<input type="hidden" name="action" value="remove">
												<button class="delete" type="submit"><i class="fa fa-close"></i></button>
												</form>
											</div>
											<?php endforeach; ?>
										<?php endif; ?>
										</div>

										<div class="cart-summary">
										<small><?= $totalQuantity ?> Item(s) selected</small>
										<h5>SUBTOTAL: <?= number_format($totalPrice, 2) ?> TND</h5>
										</div>
										<div class="cart-btns">
										<a href="/electro/pages/cart.php">View Cart</a>
										<a href="/electro/pages/checkout.php">Checkout <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
									</div>
									<!-- /Cart -->


								
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
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'laptop.php' ? 'active' : '' ?>"><a href="/electro/pages/laptop.php">Laptop</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'cameras.php' ? 'active' : '' ?>"><a href="/electro/pages/cameras.php">Cameras</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'accessories.php' ? 'active' : '' ?>"><a href="/electro/pages/accessories.php">Accessories</a></li>
						<li class="<?= basename($_SERVER['PHP_SELF']) == 'smartphone.php' ? 'active' : '' ?>"><a href="/electro/pages/smartphone.php">Smartphones</a></li>

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