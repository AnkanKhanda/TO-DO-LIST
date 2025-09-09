<?php
session_start();
include "database/db.php";

// ✅ Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Add new task
if (isset($_POST['add_task'])) {
    $task = trim($_POST['task']);
    if ($task !== "") {
        $sql = "INSERT INTO todos (user_id, task) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $task);
        $stmt->execute();
    }
}

// ✅ Mark as done
if (isset($_GET['done'])) {
    $id = (int)$_GET['done'];
    $sql = "UPDATE todos SET status='done' WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
}

// ✅ Delete task
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM todos WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
}

// ✅ Fetch tasks
$sql = "SELECT * FROM todos WHERE user_id=? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <!-- Navbar -->
  <nav class="bg-indigo-600 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Todo Dashboard</h1>
    <div>
      <span class="mr-4">Hi, <?= htmlspecialchars($_SESSION['username']); ?> 👋</span>
      <a href="logout.php" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">Logout</a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg">
    <!-- Add Task Form -->
    <form method="POST" class="flex mb-6">
      <input type="text" name="task" placeholder="Enter a new task..." 
        class="flex-grow px-4 py-2 border rounded-l-lg focus:ring-2 focus:ring-indigo-500" required>
      <button type="submit" name="add_task" 
        class="bg-indigo-600 text-white px-6 rounded-r-lg hover:bg-indigo-700 transition">Add</button>
    </form>

    <!-- Task List -->
    <ul>
      <?php if (count($tasks) > 0): ?>
        <?php foreach ($tasks as $task): ?>
          <li class="flex justify-between items-center mb-3 p-3 rounded-lg border 
            <?= $task['status'] === 'done' ? 'bg-green-100 line-through text-gray-500' : 'bg-gray-50'; ?>">
            
            <span><?= htmlspecialchars($task['task']); ?></span>
            <div class="flex space-x-2">
              <?php if ($task['status'] === 'pending'): ?>
                <a href="?done=<?= $task['id']; ?>" 
                   class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Done</a>
              <?php endif; ?>
              <a href="?delete=<?= $task['id']; ?>" 
                 class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</a>
            </div>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center text-gray-500">No tasks yet. Add one above 👆</p>
      <?php endif; ?>
    </ul>
  </div>
</body>
</html>
