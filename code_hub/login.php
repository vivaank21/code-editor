<?php
session_start();
require_once "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $username, $hashedPassword);

    if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "⚠️ Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🔑 Login | Offline Code Editor</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Loader CSS -->
  <link rel="stylesheet" href="assets/loader2.css">
</head>
<body class="deep-ocean starry flex justify-center items-center min-h-screen">

  <div class="card bg-white p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-[0_10px_30px_rgba(59,130,246,0.6)] hover:border hover:border-blue-400 w-96">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">🔑 Login</h2>

    <?php if($message): ?>
      <div class="alert alert-danger text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <input type="email" name="email" placeholder="📧 Email" class="form-control py-2 px-3 rounded-lg shadow-sm" required>

      <!-- Password with toggle -->
      <div class="input-group">
        <input type="password" id="password" name="password" placeholder="🔒 Password" class="form-control py-2 px-3 rounded-lg shadow-sm" required>
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁️</button>
      </div>

      <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">➡️ Login</button>
    </form>

    <p class="text-center mt-4">🆕 No account? 
      <a href="signup.php" class="text-blue-600 font-semibold hover:underline">📝 Sign Up</a>
    </p>
  </div>

  <script>
    function togglePassword() {
      const pass = document.getElementById("password");
      const btn = event.currentTarget;
      if (pass.type === "password") {
        pass.type = "text";
        btn.textContent = "🙈";
      } else {
        pass.type = "password";
        btn.textContent = "👁️";
      }
    }
  </script>

</body>
</html>
