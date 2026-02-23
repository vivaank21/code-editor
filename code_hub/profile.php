<?php
session_start();
require_once "includes/db.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user info
$stmt = $conn->prepare("SELECT username, email, profile_photo, bio FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_photo, $bio);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>👤 Profile | Offline Code Editor</title>
  <!-- Bootstrap + Tailwind -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      darkMode: 'class'
    }
  </script>

  <style>
    body {
      background-color: #004F59; /* Midnight Teal */
    }
    body.dark {
      background-color: #0f172a; /* Dark mode navy */
    }
  </style>
</head>
<body class="min-h-screen flex flex-col text-white transition-colors duration-500">

  <!-- Navbar -->
  <nav class="w-full flex justify-between items-center px-6 py-4 bg-transparent">
    <div class="text-2xl font-bold flex items-center gap-2 drop-shadow-md">
      ✍️ Code Hub
    </div>
    <div class="flex gap-6 text-lg font-medium">
      <a href="dashboard.php" class="hover:text-yellow-300 transition">📊 Dashboard</a>
      <a href="editor.php" class="hover:text-yellow-300 transition">💻 Editor</a>
      <a href="logout.php" class="hover:text-yellow-300 transition">🚪 Logout</a>
    </div>
    <div class="flex items-center gap-4">
      <button id="themeToggle" class="px-3 py-2 rounded-lg shadow-md border border-white hover:scale-105 transition bg-white/20 backdrop-blur-md">
        🌙
      </button>
    </div>
  </nav>

  <!-- Profile Content -->
  <main class="flex-grow flex flex-col justify-center items-center text-center">
    <h1 class="text-4xl font-extrabold mb-6 drop-shadow-md">👤 Your Profile</h1>

    <div class="bg-white/10 dark:bg-gray-800/40 p-8 rounded-2xl shadow-lg w-[32rem] backdrop-blur-md">
      <!-- Profile Photo -->
      <div class="flex flex-col items-center mb-6">
        <img src="<?= htmlspecialchars($profile_photo); ?>" 
             alt="Profile Photo" 
             class="w-28 h-28 rounded-full object-cover shadow-lg border-4 border-white/30 mb-3">
        <a href="edit_profile.php" class="text-sm text-yellow-300 hover:underline">📸 Change Photo</a>
      </div>

      <!-- User Info -->
      <p class="text-lg mb-2"><strong>🧑 Username:</strong> <?= htmlspecialchars($username); ?></p>
      <p class="text-lg mb-4"><strong>📧 Email:</strong> <?= htmlspecialchars($email); ?></p>

      <!-- Bio -->
      <div class="mb-6">
        <p class="text-lg font-semibold">📝 Bio</p>
        <p class="italic text-gray-200 dark:text-gray-300"><?= nl2br(htmlspecialchars($bio)); ?></p>
      </div>

      <a href="edit_profile.php" class="btn btn-warning w-full py-2 rounded-lg shadow hover:scale-105 transition">✏️ Edit Profile</a>
    </div>
  </main>

  <script>
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;

    if(localStorage.getItem("theme") === "dark") {
      body.classList.add("dark");
      themeToggle.textContent = "☀️";
    }

    themeToggle.addEventListener("click", () => {
      body.classList.toggle("dark");
      if(body.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
        themeToggle.textContent = "☀️";
      } else {
        localStorage.setItem("theme", "light");
        themeToggle.textContent = "🌙";
      }
    });
  </script>

</body>
</html>
