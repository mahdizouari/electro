<?php

session_start();
require_once '/opt/lampp/htdocs/electro/pages/includes/pdo.php';


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email is already registered.';
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$name, $email, $hashed_password]);

            $success = 'Registration successful! You can now <a href="login.php">login</a>.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Register</title>
<style>
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
    width: 340px;
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
  input[type="text"],
  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    border: 1.8px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s ease;
  }
  input[type="text"]:focus,
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
  p.success {
    color: #2e7d32;
    background: #d0f0d9;
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

<form method="post" action="register.php" id="registerForm" novalidate>
  <h2>Register</h2>

  <?php if (!empty($error)): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
  <p class="success"><?= $success ?></p>
  <?php endif; ?>

  <label for="name">Name:
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
  </label>

  <label for="email">Email:
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
  </label>

  <label for="password">Password:
    <input type="password" id="password" name="password" required minlength="6">
  </label>

  <label for="confirm_password">Confirm Password:
    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
  </label>

  <button type="submit">Register</button>

  <div class="links">
    <a href="/electro">Homepage</a> |
    <a href="/electro/pages/login.php">Login</a>
  </div>
</form>

<script>
  document.getElementById('registerForm').addEventListener('submit', function(event) {
    const name = this.name.value.trim();
    const email = this.email.value.trim();
    const password = this.password.value.trim();
    const confirmPassword = this.confirm_password.value.trim();

    if (!name || !email || !password || !confirmPassword) {
      alert('Please fill in all fields.');
      event.preventDefault();
      return;
    }
    if (password.length < 6) {
      alert('Password must be at least 6 characters.');
      event.preventDefault();
      return;
    }
    if (password !== confirmPassword) {
      alert('Passwords do not match.');
      event.preventDefault();
      return;
    }
    // basic email format validation (additional server-side validation always needed)
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      alert('Invalid email format.');
      event.preventDefault();
      return;
    }
  });
</script>

</body>
</html>

