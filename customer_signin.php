<?php
// Connect to database
include '../db/connection.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT CustomerID, Password FROM Customer WHERE Username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        // ⚠️ Replace with password_verify($password, $customer['Password']) if passwords are hashed
        if ($customer && $password === $customer['Password']) {
            $_SESSION['CustomerID'] = $customer['CustomerID'];
            header("Location: customer_reservation.php?id=" . $customer['CustomerID']);
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Sign In</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #DDF4E7, #67C090, #26667F, #124170);
      background-attachment: fixed;
      min-height: 100vh;
      overflow-x: hidden;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .floating-shape {
      position: absolute;
      border-radius: 50%;
      opacity: 0.15;
    }
  </style>
</head>
<body class="text-gray-800">

  <!-- Decorative Background Shapes -->
  <div class="floating-shape w-40 h-40 bg-white top-10 left-10"></div>
  <div class="floating-shape w-56 h-56 bg-white bottom-20 right-20"></div>

  <!-- Navbar -->
  <nav class="fixed top-0 left-0 w-full bg-white/20 backdrop-blur-lg shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-[#124170]">Crystal Heaven</h1>
      <ul class="flex space-x-6 text-[#124170] font-medium">
        <li><a href="home.php" class="hover:text-[#67C090] transition">Home</a></li>
      </ul>
    </div>
  </nav>

  <!-- Sign In Form -->
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="glass-card rounded-2xl shadow-2xl w-full max-w-md p-8 mt-20 animate-fade-in">
      <h2 class="text-3xl font-bold text-center text-[#124170] mb-6">Customer Sign In</h2>

      <?php if ($error): ?>
        <div class="bg-red-500 text-white text-sm p-3 mb-4 rounded-lg animate-bounce">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-6">
        <div>
          <label class="block mb-2 text-[#124170] font-semibold">Username</label>
          <input type="text" name="username" required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#67C090] focus:border-transparent transition">
        </div>

        <div>
          <label class="block mb-2 text-[#124170] font-semibold">Password</label>
          <input type="password" name="password" required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#67C090] focus:border-transparent transition">
        </div>

        <button type="submit"
          class="w-full bg-[#26667F] text-white py-3 rounded-lg font-semibold hover:bg-[#67C090] hover:scale-105 transition transform shadow-md">
          Sign In
        </button>
      </form>

      <div class="mt-6 flex justify-between text-sm text-[#124170]">
        <a href="#" class="hover:underline">.</a>
        <a href="cutomer_signup.php" class="hover:underline">Don’t have an account? Sign Up</a>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-white/20 backdrop-blur-lg py-6 text-center text-[#124170] text-sm mt-10">
    © <?php echo date("Y"); ?> HotelX. All Rights Reserved.
  </footer>

  <script src="../js/customer_signin.js" defer></script>
</body>
</html>
