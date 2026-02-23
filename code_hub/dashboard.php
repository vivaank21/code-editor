<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📊 Dashboard | Offline Code Editor</title>
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
    <!-- Left: Logo / Title -->
    <div class="text-2xl font-bold flex items-center gap-2 drop-shadow-md">
      ✍️ Code Hub
    </div>

    <!-- Center: Links -->
    <div class="flex gap-6 text-lg font-medium">
      <a href="editor.php" class="hover:text-yellow-300 transition">💻 Editor</a>
      <a href="profile.php" class="hover:text-yellow-300 transition">⚙️ Profile</a>
      <a href="logout.php" class="hover:text-yellow-300 transition">🚪 Logout</a>
    </div>

    <!-- Right: User + Theme Toggle -->
    <div class="flex items-center gap-4">
      <span class="font-semibold drop-shadow-md">👋 <?= $_SESSION['username']; ?></span>
      <button id="themeToggle" class="px-3 py-2 rounded-lg shadow-md border border-white hover:scale-105 transition bg-white/20 backdrop-blur-md">
        🌙
      </button>
    </div>
  </nav>

  <!-- Dashboard Body -->
  <main class="flex-grow flex flex-col justify-center items-center text-center">
    <h1 class="text-4xl font-extrabold mb-4 drop-shadow-md">Welcome, <?= $_SESSION['username']; ?>!</h1>
    <p class="text-lg font-medium max-w-xl">
      ✨ Every great app starts with a single line of code 💻. Today could be the day 🚀.
    </p>
  </main>

  <script>
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;

    // Load saved theme
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
