<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Commande Overview</title>
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

  <style>
    h1 {
      color: #d10024;
      margin-bottom: 40px;
      font-weight: 1000;
      font-size: 4rem;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Clean modern font */
      text-align: center;
      letter-spacing: 3px;
      margin-top: 20px;
    }

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
}

  </style>
</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/electro/pages/includes/header.php'); ?>
<?php
require_once '/opt/lampp/htdocs/electro/pages/includes/pdo.php';

// Admin-only access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

// Get all cart records with user and product info
$stmt = $pdo->query("
    SELECT 
        cart.id AS cart_id,
        cart.name,
        products.name AS product_name,
        products.price,
        products.image,
        cart.date,
        cart.numero,
        cart.quantity,
        cart.lastname,
        cart.adress,
        cart.city
    FROM cart
    JOIN products ON cart.product_id = products.id
    ORDER BY cart.date DESC
");

$cart_items = $stmt->fetchAll();
?>




<h1>Commande Overview</h1>

<table>
  <thead>
    <tr>
      <th>Cart ID</th>
      <th>Name</th>
      <th>Last Name</th>
      <th>Adress</th>
      <th>City</th>
      <th>Numero</th>
      <th>Product</th>
      <th>Quantity</th>
      <th>Price (TND)</th>
      <th>Total</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($cart_items): ?>
      <?php foreach ($cart_items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['cart_id']) ?></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?=htmlspecialchars($item['lastname'])?></td>
        <td><?=htmlspecialchars($item['adress'])?></td>
        <td><?=htmlspecialchars($item['city'])?></td>
        <td><?=htmlspecialchars($item['numero'])?></td>
       <td>
  <?php if (!empty($item['image'])): ?>
    <img src="/electro/<?= htmlspecialchars($item['image']) ?>" alt="Product" style="max-height: 40px; vertical-align: middle; margin-right: 10px;">
  <?php endif; ?>
  <?= htmlspecialchars($item['product_name']) ?>
</td>
        <td><?=htmlspecialchars($item['quantity'])?></td>
        <td><?= htmlspecialchars(number_format($item['price'], 2)) ?></td>
        <td><?= htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)) ?></td>
        <td><?= htmlspecialchars($item['date']) ?></td>
        <td><a href="delete_commande.php?id=<?= htmlspecialchars($item['cart_id']) ?> "onclick="return confirm('Are you sure you want to remove this item?')">Delete</a></td>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7">No cart records found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>