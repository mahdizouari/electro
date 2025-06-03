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


<?php


session_start();
require '/opt/lampp/htdocs/electro/pages/includes/pdo.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    $image = $_FILES['image'] ?? null;
    $category = trim($_POST['category'] ?? '');

    if (!$name || $price <= 0 || !$category) {
        $error = "Name, valid price, and category are required.";
    } else {
        // Handle image upload
        $imagePath = '';
        if ($image && $image['tmp_name']) {
            $filename = 'uploads/' . time() . '_' . basename($image['name']);
            $destination = '/opt/lampp/htdocs/electro/' . $filename;

            if (move_uploaded_file($image['tmp_name'], $destination)) {
                $imagePath = $filename;
            } else {
                $error = "Failed to upload image. Check folder permissions.";
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity, category, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $description, $price, $quantity, $category, $imagePath]);

            $success_message = "Product updated successfully.";

        }
    }
}

// If flash message is set, grab and clear it
if (isset($_SESSION['flash_message'])) {
    $success = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>

<!-- HTML Part of your form page, to display errors and success messages -->

<?php if ($error): ?>
    <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div style="color: green; margin-bottom: 15px;"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Your form goes here -->




<title>Add New Product</title>
<style>


h1 {
  color: #d10024;            /* Bold red color */
  margin-bottom: 40px;       /* Space below the heading */
  font-weight: 1000;          /* Bold font */
  font-size: 3rem;         /* Large font size */
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Clean modern font */
  text-align: center;        /* Center the heading */
  letter-spacing: 3px;       /* Slight spacing between letters */
  margin-top: 20px;
}


#edit-product-form {
  background: #ffffff;
  max-width: 480px;
  margin: 0 auto 30px auto;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  gap: 18px;
}

label {
  font-weight: 600;
  display: flex;
  flex-direction: column;
  font-size: 1.4rem;
  color: #444;
}

input[type="text"],
input[type="number"],
textarea {
  margin-top: 6px;
  padding: 10px 12px;
  border: 1.5px solid #ccc;
  border-radius: 6px;
  font-size: 1.4rem;
  resize: vertical;
  transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
  border-color: #d10024;
  outline: none;
}

textarea {
  min-height: 100px;
}

button[type="submit"] {
  background-color: #d10024;
  color: white;
  border: none;
  padding: 14px 0;
  font-size: 1.4rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 700;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

button[type="submit"]:hover {
  background-color: #a3001b;
}

.error {
  max-width: 480px;
  margin: 0 auto 20px auto;
  background-color: #ffe0e0;
  border: 1px solid #d10024;
  color: #a3001b;
  padding: 12px 15px;
  border-radius: 8px;
  font-weight: 600;
  text-align: center;
}

p a {
  display: block;
  max-width: 480px;
  margin: 0 auto;
  color: #d10024;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
  text-align: center;
}

p a:hover {
  color: #a3001b;
}

</style>


<h1>Add New Product</h1>
<?php if ($error): ?>
<p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" id="edit-product-form" enctype="multipart/form-data">
<?php if ($success_message): ?>
    <div class="flash-message" style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:15px;">
        <?= htmlspecialchars($success_message) ?>
    </div>
<?php endif; ?>
  <label>Name:
    <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
  </label>

  <label>Description:
    <textarea name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
  </label>

  <label>Price (TND):
    <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
  </label>

  <label>Quantity:
    <input type="number" name="quantity" min="0" value="<?= htmlspecialchars($_POST['quantity'] ?? '1') ?>">
  </label>
  <label for="category">Category:</label>
    <select name="category" id="category" required>
        <option value="">--Select Category--</option>
        <option value="Laptops">Laptops</option>
        <option value="Smartphones">Smartphones</option>
        <option value="Cameras">Cameras</option>
        <option value="Accessories">Accessories</option>
    </select>

  <label>Image:
    <input type="file" name="image" accept="image/*">
  </label>

  <button type="submit">Add Product</button>
</form>


<p><a href="/electro/pages/dashboard.php">&larr; Back to Dashboard</a></p>




<!-- jQuery Plugins -->
<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>

