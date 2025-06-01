
<?php
session_start();

// Sample cart initialization for demo (remove this on production)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        101 => [
            'name' => 'Basic T-shirt',
            'size' => 'M',
            'color' => 'Grey',
            'price' => 20,
            'quantity' => 2,
            'image' => 'https://i.imgur.com/XiFJkhI.jpg'
        ],
        102 => [
            'name' => 'Casual Shirt',
            'size' => 'L',
            'color' => 'Blue',
            'price' => 25,
            'quantity' => 1,
            'image' => 'https://i.imgur.com/XiFJkhI.jpg'
        ],
    ];
}

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
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Calculate total
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shopping Cart</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
  />
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/header.php'); ?>
    <?php
session_start();
if (isset($_SESSION['flash_message'])) {
    echo '
    <div style="
        display: flex;
        justify-content: center;
        margin-top: 20px;
    ">
        <div style="
            background-color: #e6f9ed;
            color: #256029;
            border: 1px solid #b6e2c8;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
            text-align: center;
        ">
            ' . htmlspecialchars($_SESSION['flash_message']) . '
        </div>
    </div>';
    unset($_SESSION['flash_message']);
}
?>

  <style>
    /* Your CSS from earlier exactly */
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200&display=swap');

    body {
      font-family: 'Manrope', sans-serif;
      background: #eee;
      margin: 0;
      padding: 0;
    }

    .size span {
      font-size: 11px;
    }

    .color span {
      font-size: 11px;
    }

    .product-details {
      margin-right: 70px;
      text-align: center;
    }

    .gift-card:focus {
      box-shadow: none;
      outline: none;
    }

    .pay-button {
      color: #fff;
      background-color: #f0ad4e;
      border: none;
      width: 100%;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .pay-button:hover {
      background-color: #ec971f;
      color: #fff;
    }

    .pay-button:focus {
      color: #fff;
      box-shadow: none;
      outline: none;
    }

    .text-grey {
      color: #a39f9f;
    }

    .qty i {
      font-size: 15px;
      cursor: pointer;
      user-select: none;
      padding: 0 8px;
      border: none;
      background: none;
    }

    .qty h5 {
      margin: 0 8px;
      font-weight: 500;
      display: inline-block;
      min-width: 24px;
      text-align: center;
    }

    /* Container */
    .container {
      max-width: 900px;
      margin: auto;
      padding: 20px;
    }

    .product-card {
      background: #fff;
      padding: 15px 20px;
      border-radius: 10px;
      margin-top: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
    }

    .product-card img {
      border-radius: 8px;
      width: 70px;
      height: 70px;
      object-fit: cover;
    }

    .product-desc {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 4px;
    }

    .qty {
      display: flex;
      align-items: center;
      font-weight: 600;
    }

    .fa-trash {
      cursor: pointer;
      font-size: 20px;
      transition: color 0.2s ease;
      color: #d9534f;
    }

    .fa-trash:hover {
      color: #b52b27;
    }

    /* Sorting container */
    .sorting {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      font-weight: 600;
      color: #555;
      margin-bottom: 10px;
    }

    .sorting i {
      margin-left: 5px;
      cursor: pointer;
    }

    /* Discount input + apply button */
    .discount-section {
      margin-top: 30px;
      display: flex;
      gap: 10px;
    }

    .gift-card {
      flex: 1;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 576px) {
      .product-card {
        flex-direction: column;
        gap: 12px;
        text-align: center;
      }

      .product-details {
        margin-right: 0;
      }

      .product-desc {
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <div class="container mt-5 mb-5">
    <h4>Shopping cart</h4>
    <div class="sorting">
      <span>Sort by:</span>
      <span class="ml-2 font-weight-bold">Price</span>
      <i class="fa fa-angle-down"></i>
    </div>

    <?php if (empty($_SESSION['cart'])): ?>
      <p>Your cart is empty.</p>
    <?php else: ?>
      <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
        <div class="product-card">
          <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
          <div class="product-details">
            <span class="font-weight-bold"><?= htmlspecialchars($item['name']) ?></span>
            <div class="product-desc">
              <div class="size"><span class="text-grey">Size:</span><span>&nbsp;<?= htmlspecialchars($item['size']) ?></span></div>
              <div class="color"><span class="text-grey">Color:</span><span>&nbsp;<?= htmlspecialchars($item['color']) ?></span></div>
            </div>
          </div>

          <!-- Quantity update form -->
          <div class="qty">
            <form method="post" style="display:inline;">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <input type="hidden" name="action" value="decrease">
              <button type="submit" class="fa fa-minus text-danger" aria-label="Decrease quantity"></button>
            </form>

            <h5 class="text-grey mt-1 mr-1 ml-1"><?= $item['quantity'] ?></h5>

            <form method="post" style="display:inline;">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <input type="hidden" name="action" value="increase">
              <button type="submit" class="fa fa-plus text-success" aria-label="Increase quantity"></button>
            </form>
          </div>

          <div>
            <h5 class="text-grey">$<?= number_format($item['price'] * $item['quantity'], 2) ?></h5>
          </div>

          <div class="d-flex align-items-center">
            <form method="post" style="display:inline;">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <input type="hidden" name="action" value="remove">
              <button type="submit" class="fa fa-trash" aria-label="Remove item"></button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Discount Code -->
      <div class="discount-section">
        <input
          type="text"
          class="gift-card"
          placeholder="discount code/gift card"
          name="discount_code"
          disabled
        />
        <button class="btn btn-outline-warning btn-sm" type="button" disabled>
          Apply
        </button>
      </div>

      <!-- Total and Pay -->
      <div class="mt-3">
        <h4>Total: $<?= number_format($totalPrice, 2) ?></h4>
        <button class="pay-button" type="button" disabled>Proceed to Pay</button>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
