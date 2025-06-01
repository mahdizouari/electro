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

// Check if admin logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Fetch all products
$stmt = $pdo->query("SELECT id, name, price, quantity, image, category, created_at FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();

?>


<title>Admin Dashboard</title>

<h1>Admin Dashboard</h1>

<div class="top-bar">
  <a href="/electro/pages/crud/product_create.php" class="button">+ Add New Product</a>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Image</th>
      <th>Name</th>
      <th>Price (TND)</th>
      <th>Quantity</th>
      <th>Category</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($products): ?>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['id']) ?></td>
        <td>
          <?php if (!empty($p['image'])): ?>
            <img src="/electro/<?= htmlspecialchars($p['image']) ?>" alt="Image" style="max-height: 50px;">
          <?php else: ?>
            <span>No image</span>
          <?php endif; ?>
        </td>

        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= htmlspecialchars(number_format($p['price'], 2)) ?></td>
        <td><?= htmlspecialchars($p['quantity']) ?></td>
        <td><?= htmlspecialchars($p['category'] ?? 'No category') ?></td>
        <td><?= htmlspecialchars($p['created_at']) ?></td>
        <td>
          <a href="/electro/pages/crud/product_edit.php?id=<?= $p['id'] ?>" class="button">Edit</a>
          <a href="/electro/pages/crud/product_delete.php?id=<?= $p['id'] ?>" class="button" style="background:#a3001b;">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7">No products found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<style>
    /* Reset and base styles */


h1 {
  color: #d10024;            /* Bold red color */
  margin-bottom: 40px;       /* Space below the heading */
  font-weight: 1000;          /* Bold font */
  font-size: 4rem;         /* Large font size */
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Clean modern font */
  text-align: center;        /* Center the heading */
  letter-spacing: 3px;       /* Slight spacing between letters */
  margin-top: 20px;
}


/* Top bar with buttons */
.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;

  max-width: 900px;  /* or any preferred width */
  margin: 20px auto; /* centers horizontally */
  padding: 0 10px;   /* optional: some horizontal padding */
}


.top-bar a.button {
  background-color: #d10024;
  color: #fff;
  padding: 10px 18px;
  text-decoration: none;
  font-weight: 600;
  border-radius: 5px;
  box-shadow: 0 3px 6px rgb(209 0 36 / 0.4);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  white-space: nowrap;
}

.top-bar a.button:hover {
  background-color: #a3001b;
  box-shadow: 0 5px 12px rgb(163 0 27 / 0.6);
}

.top-bar a.button[style] {
  background-color: #555 !important;
  box-shadow: 0 3px 6px rgb(85 85 85 / 0.4);
}

.top-bar a.button[style]:hover {
  background-color: #333 !important;
  box-shadow: 0 5px 12px rgb(51 51 51 / 0.6);
}

/* Table styles */
table {
  width: 70%;
  border-collapse: collapse;
  background-color: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  margin: 20px auto; /* This centers the table horizontally */
}

thead {
  background-color: #d10024;
  color: white;
  font-weight: 1000;
}

thead th {
  padding: 14px 20px;
  text-align: left;
  font-size: 2rem;
  border-bottom: 2px solid #a3001b;
}

tbody tr {
  border-bottom: 1px solid #ddd;
  transition: background-color 0.2s ease;
}

tbody tr:hover {
  background-color: #f2f2f2;
}

tbody td {
  padding: 14px 20px;
  font-size: 1.4rem;
  vertical-align: middle;
}

/* Action buttons inside the table */
tbody a.button {
  padding: 6px 14px;
  font-size: 1.4rem;
  border-radius: 4px;
  box-shadow: none;
}

tbody a.button:hover {
  box-shadow: 0 0 8px rgba(209, 0, 36, 0.5);
}

tbody a.button[style] {
  background-color: #a3001b !important;
  color: white;
}

tbody a.button[style]:hover {
  background-color: #800016 !important;
  box-shadow: 0 0 10px rgba(128, 0, 22, 0.7);
}

/* Responsive adjustments */
@media (max-width: 600px) {
  table, thead, tbody, tr, th, td {
    display: block;
  }

  thead tr {
    display: none;
  }

  tbody tr {
    margin-bottom: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 15px;
  }

  tbody td {
    padding-left: 50%;
    position: relative;
    text-align: right;
    font-size: 0.9rem;
  }

  tbody td::before {
    position: absolute;
    left: 15px;
    width: 45%;
    white-space: nowrap;
    font-weight: 600;
    text-align: left;
    color: #666;
  }

  tbody td:nth-of-type(1)::before { content: "ID"; }
  tbody td:nth-of-type(2)::before { content: "Name"; }
  tbody td:nth-of-type(3)::before { content: "Price"; }
  tbody td:nth-of-type(4)::before { content: "Created At"; }
  tbody td:nth-of-type(5)::before { content: "Actions"; }

  tbody a.button {
    display: inline-block;
    margin: 5px 5px 0 0;
  }

  .top-bar {
    flex-direction: column;
    align-items: stretch;
  }

  .top-bar a.button {
    width: 100%;
    text-align: center;
  }
}

</style>


<!-- jQuery Plugins -->
<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>

