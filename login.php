<?php
session_start();
include "database/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-2xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    <?php if(isset($error)): ?>
      <p class="text-red-500 mb-4 text-center"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 mb-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 mb-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">Login</button>
    </form>
    <p class="text-center mt-4 text-gray-600">Don’t have an account? 
      <a href="register.php" class="text-indigo-600 font-semibold">Register</a>
    </p>
  </div>
</body>
</html>
