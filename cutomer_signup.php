<?php
// Connect to database
include '../db/connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $nic = $_POST['nic'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $conn->prepare("INSERT INTO Customer (FirstName, LastName, NIC, Address, Email, PhoneNo, Username, Password) 
                                VALUES (:firstName, :lastName, :nic, :address, :email, :phone, :username, :password)");
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':nic' => $nic,
            ':address' => $address,
            ':email' => $email,
            ':phone' => $phone,
            ':username' => $username,
            ':password' => $password
        ]);
        $successMessage = "Registration successful! You can now sign in.";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
      body { font-family: 'Poppins', sans-serif; }

      /* Background image with overlay */
      body {
          background: url('../images/hotel1.jpg') center/cover no-repeat;
          position: relative;
      }
      

      .glass-card {
          backdrop-filter: blur(12px);
          background: rgba(255, 255, 255, 0.85);
          z-index: 10;
      }

      /* Glow inputs */
      .glow-input:focus {
          box-shadow: 0 0 15px rgba(102,192,144,0.8);
          transform: scale(1.02);
          transition: all 0.3s ease;
      }

      /* Navigation bar specific styles */
      nav {
          background-color: rgba(34, 102, 127, 0.85); /* #26667F with opacity */
          backdrop-filter: blur(8px);
          z-index: 20;
      }
      .nav-item {
          color: #DDF4E7; /* light text */
          transition: all 0.3s ease;
      }
      .nav-item:hover {
          color: #67C090; /* highlight on hover */
      }

      /* Animations */
      @keyframes floatBg {
          0%,100% { transform: translateY(0); }
          50% { transform: translateY(-20px); }
      }
      .animate-bg {
          animation: floatBg 15s ease-in-out infinite;
      }
  </style>
</head>
<body class="relative min-h-screen flex flex-col justify-between text-gray-800 overflow-x-hidden">

  <!-- Navigation -->
  <nav class="w-full py-4 fixed top-0 left-0 shadow-md">
    <div class="container mx-auto flex justify-between items-center px-6">
      <div class="text-xl font-bold text-white">🏨 Crystal Heaven</div>
      <ul class="hidden md:flex items-center gap-6 text-sm font-medium">
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="#features">Home</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="#rooms">Sign In</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="customer_signup.php">Sign Up</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="#contact">Contact</a></li>
      </ul>
    </div>
  </nav>

  <!-- Main Form -->
  <main class="flex justify-center items-center flex-grow px-4 pt-24 z-10 relative">
      <div class="glass-card rounded-2xl shadow-2xl p-8 w-full max-w-lg animate-fadeIn">
          <h2 class="text-2xl font-semibold text-center mb-6 text-[#124170]">Create Your Account</h2>

          <?php if (!empty($successMessage)): ?>
              <p class="bg-green-600 text-white text-center py-2 rounded mb-4"><?php echo $successMessage; ?></p>
          <?php elseif (!empty($errorMessage)): ?>
              <p class="bg-red-600 text-white text-center py-2 rounded mb-4"><?php echo $errorMessage; ?></p>
          <?php endif; ?>

          <form action="" method="POST" class="space-y-4">
              <input type="text" name="first_name" placeholder="First Name" required
                    class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />

                <input type="text" name="last_name" placeholder="Last Name" required
                    class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />

              <input type="text" name="nic" placeholder="National ID" required
                     class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />
              <input type="text" name="address" placeholder="Address" required
                     class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />
              <input type="email" name="email" placeholder="Email Address" required
                     class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />
              <input type="tel" name="phone" placeholder="Mobile Phone Number" required
                     class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />
              <input type="text" name="username" placeholder="Username" required
                     class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />
              <input type="password" name="password" placeholder="Password" required
                     class="glow-input w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none" />

              <button type="submit"
                      class="w-full py-3 bg-gradient-to-r from-[#67C090] to-[#124170] rounded-md font-bold text-white hover:scale-105 hover:shadow-lg transition-all duration-300">
                  Sign Up
              </button>
          </form>

          <p class="text-center mt-4">
              Already have an account?
              <a href="customer_signin.php" class="text-[#26667F] hover:underline hover:text-[#124170] transition">Sign In</a>
          </p>
      </div>
  </main>

  <!-- Footer -->
  <footer class="text-center py-4 text-[#124170] text-sm z-10 relative">
      © <?php echo date("Y"); ?> Hotel System. All rights reserved.
  </footer>

  <!-- External JS -->
  <script src="../js/customer_signup.js"></script>
</body>
</html>
