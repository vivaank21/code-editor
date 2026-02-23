<?php
session_start();
require_once "includes/db.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Handle update form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);

    // Handle profile photo upload
    $profile_photo = null;
    if (!empty($_FILES["profile_photo"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . time() . "_" . basename($_FILES["profile_photo"]["name"]);
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $profile_photo = $target_file;
        }
    }

    if ($profile_photo) {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, bio=?, profile_photo=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $email, $bio, $profile_photo, $_SESSION['user_id']);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, bio=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $bio, $_SESSION['user_id']);
    }

    if ($stmt->execute()) {
        $message = "✅ Profile updated successfully!";
    } else {
        $message = "❌ Error updating profile.";
    }
    $stmt->close();
}

// Fetch current info
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
  <title>✏️ Edit Profile | Offline Code Editor</title>
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
      ✍️ Offline Code Editor
    </div>
    <div class="flex gap-6 text-lg font-medium">
      <a href="dashboard.php" class="hover:text-yellow-300 transition">📊 Dashboard</a>
      <a href="profile.php" class="hover:text-yellow-300 transition">👤 Profile</a>
      <a href="logout.php" class="hover:text-yellow-300 transition">🚪 Logout</a>
    </div>
    <div class="flex items-center gap-4">
      <button id="themeToggle" class="px-3 py-2 rounded-lg shadow-md border border-white hover:scale-105 transition bg-white/20 backdrop-blur-md">
        🌙
      </button>
    </div>
  </nav>

  <!-- Edit Profile Form -->
  <main class="flex-grow flex flex-col justify-center items-center text-center">
    <h1 class="text-4xl font-extrabold mb-6 drop-shadow-md">✏️ Edit Profile</h1>

    <div class="bg-white/10 dark:bg-gray-800/40 p-8 rounded-2xl shadow-lg w-[32rem] backdrop-blur-md text-left">
      <?php if($message): ?>
        <div class="alert alert-info text-center mb-4"><?= $message ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <!-- Username -->
        <div>
          <label class="block mb-1">🧑 Username</label>
          <input type="text" name="username" value="<?= htmlspecialchars($username); ?>" class="form-control rounded-lg shadow-sm" required>
        </div>

        <!-- Email -->
        <div>
          <label class="block mb-1">📧 Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" class="form-control rounded-lg shadow-sm" required>
        </div>

        <!-- Bio -->
        <div>
          <label class="block mb-1">📝 Bio</label>
          <textarea name="bio" class="form-control rounded-lg shadow-sm" rows="3"><?= htmlspecialchars($bio); ?></textarea>
        </div>

        <!-- Profile Photo -->
        <div>
          <label class="block mb-1">📸 Profile Photo</label>
          <input type="file" name="profile_photo" accept="image/*" class="form-control">
          <?php if($profile_photo): ?>
            <img src="<?= htmlspecialchars($profile_photo); ?>" alt="Profile Photo" class="mt-3 w-20 h-20 rounded-full object-cover border-2 border-white/30 shadow">
          <?php endif; ?>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-warning w-full py-2 rounded-lg shadow hover:scale-105 transition">💾 Save Changes</button>
      </form>
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
