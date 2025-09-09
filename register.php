<?php
session_start();
include "database/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: login.php");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-2xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Create Account</h2>
    <?php if(isset($error)): ?>
      <p class="text-red-500 mb-4 text-center"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required class="w-full px-4 py-2 mb-3 border rounded-lg focus:ring-2 focus:ring-purple-500">
      <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 mb-3 border rounded-lg focus:ring-2 focus:ring-purple-500">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 mb-3 border rounded-lg focus:ring-2 focus:ring-purple-500">
      <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">Register</button>
    </form>
    <p class="text-center mt-4 text-gray-600">Already have an account? 
      <a href="login.php" class="text-purple-600 font-semibold">Login</a>
    </p>
  </div>
</body>
</html>
