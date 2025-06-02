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
        users.name,
        products.name AS product_name,
        products.price,
        products.image,
        cart.date,
        cart.numero
    FROM cart
    JOIN users ON cart.user_id = users.id
    JOIN products ON cart.product_id = products.id
    ORDER BY cart.date DESC
");

$cart_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Cart Overview</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    h1 {
      color: #d10024;
      margin-bottom: 40px;
      font-weight: 1000;
      font-size: 4rem;
      text-align: center;
      letter-spacing: 3px;
      margin-top: 20px;
    }

    table {
      width: 90%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin: 20px auto;
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

      tbody td:nth-of-type(1)::before { content: "Numero"; }
      tbody td:nth-of-type(2)::before { content: "Cart ID"; }
      tbody td:nth-of-type(3)::before { content: "User"; }
      tbody td:nth-of-type(4)::before { content: "Product"; }
      tbody td:nth-of-type(5)::before { content: "Stock"; }
      tbody td:nth-of-type(6)::before { content: "Price"; }
      tbody td:nth-of-type(7)::before { content: "Date"; }
    }
  </style>
</head>
<body>



<h1>Cart Overview</h1>

<table>
  <thead>
    <tr>
      <th>Numero</th>
      <th>Cart ID</th>
      <th>User</th>
      <th>Numero</th>
      <th>Product</th>
      <th>Price (TND)</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($cart_items): ?>
      <?php $i = 1; foreach ($cart_items as $item): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($item['cart_id']) ?></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?=htmlspecialchars($item['numero'])?></td>
       <td>
  <?php if (!empty($item['image'])): ?>
    <img src="/electro/<?= htmlspecialchars($item['image']) ?>" alt="Product" style="max-height: 40px; vertical-align: middle; margin-right: 10px;">
  <?php endif; ?>
  <?= htmlspecialchars($item['product_name']) ?>
</td>

        <td><?= htmlspecialchars(number_format($item['price'], 2)) ?></td>
        <td><?= htmlspecialchars($item['date']) ?></td>
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