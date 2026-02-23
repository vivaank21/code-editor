<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | Offline Code Editor</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Custom CSS Loader -->
  <link href="assets/loader2.css" rel="stylesheet">
</head>
<body class="deep-ocean min-h-screen flex flex-col justify-center items-center text-white">


  <!-- Loader Animation -->
  <div id="loader" class="loader fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50">
    <div class="spinner-border text-light" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="text-center space-y-6 animate-fadeIn">
    <h1 class="text-5xl font-extrabold drop-shadow-lg">Code Hub</h1>
    <p class="text-lg">Experiment with <span class="font-bold">HTML, CSS & JavaScript</span> in real-time.</p>

    <div class="flex justify-center gap-4 mt-6">
      <a href="login.php" class="btn btn-light shadow-lg px-4 py-2 rounded-xl hover:scale-110 transition-transform duration-300">Login</a>
      <a href="signup.php" class="btn btn-warning shadow-lg px-4 py-2 rounded-xl hover:scale-110 transition-transform duration-300">Sign Up</a>
      <a href="dashboard.php" class="btn btn-success shadow-lg px-4 py-2 rounded-xl hover:scale-110 transition-transform duration-300">Try Without Login</a>
    </div>
  </div>

  <!-- Footer -->
  <footer class="absolute bottom-4 text-sm opacity-80">
    <p>© <?= date("Y") ?> Code Hub | Built with PHP, MySQL, Tailwind & Bootstrap</p>
  </footer>

  <script>
    // Loader fade-out
    window.addEventListener("load", () => {
      document.getElementById("loader").style.display = "none";
    });
  </script>
</body>
</html>
