<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '/opt/lampp/htdocs/electro/pages/includes/pdo.php';

$error = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // User exists, verify password
        if (password_verify($password, $user['password'])) {
            // Password correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_role'] = $user['role'];  // after password_verify check passes


            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /electro/pages/dashboard.php'); // URL path, not file system path
                exit();
            } else {
                header('Location: /electro/index.php'); // Normal user homepage
                exit();
            }
        } else {
            $error = 'Incorrect password.';
        }
    } else {
        $error = 'User not found.';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login</title>
<style>
  /* Reset some defaults */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f2f2f2;
    display: flex;
    height: 100vh;
    justify-content: center;
    align-items: center;
    margin: 0;
  }
  form {
    background: #fff;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    width: 320px;
  }
  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
  }
  label {
    display: block;
    margin-bottom: 15px;
    font-weight: 600;
    color: #555;
  }
  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    border: 1.8px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s ease;
  }
  input[type="email"]:focus,
  input[type="password"]:focus {
    border-color: #d10024;
    outline: none;
  }
  button {
    width: 100%;
    background-color: #d10024;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    font-weight: 700;
    transition: background-color 0.3s ease;
  }
  button:hover {
    background-color: #a3001b;
  }
  p.error {
    color: #d10024;
    background: #ffe5e5;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    font-weight: 600;
  }
  .links {
    margin-top: 20px;
    text-align: center;
  }
  .links a {
    margin: 0 10px;
    text-decoration: none;
    color: #d10024;
    font-weight: 600;
  }
  .links a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<form method="post" action="login.php" id="loginForm" novalidate>
  <h2>Login</h2>

  <?php if (!empty($error)): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <label for="email">Email:
    <input type="email" id="email" name="email" required>
  </label>

  <label for="password">Password:
    <input type="password" id="password" name="password" required minlength="6">
  </label>

  <button type="submit">Login</button>

  <div class="links">
    <a href="/electro">Homepage</a> |
    <a href="/electro/pages/register.php">Register</a>
  </div>
</form>

<script>
  // Simple client-side validation before submit
  document.getElementById('loginForm').addEventListener('submit', function(event) {
    const email = this.email.value.trim();
    const password = this.password.value.trim();
    if (!email || !password) {
      alert('Please fill in both email and password.');
      event.preventDefault();
    }
  });
</script>

</body>
</html>
