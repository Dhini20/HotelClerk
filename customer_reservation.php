<?php
// Connect to database
include '../db/connection.php';

// Capture CustomerID from login redirect
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Customer ID not found. Please login again.");
}
$customerID = intval($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Packages - Customer</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Inter Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #DDF4E7 0%, #67C090 35%, #26667F 70%, #124170 100%);
      background-attachment: fixed;
    }
  </style>
</head>
<body class="text-gray-900">

  <!-- Navbar -->
  <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md shadow-md z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="text-2xl font-bold text-[#124170]">Crystal Heaven</div>
      <ul class="flex space-x-8 text-[#26667F] font-semibold">
        <li><a href="home.php" class="hover:text-[#67C090] transition">Home</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="pt-32 pb-20 text-center text-white relative">
    <h1 class="text-4xl md:text-6xl font-bold drop-shadow-lg mb-6 animate-fadeIn">Choose Your Perfect Stay</h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto opacity-90 animate-slideUp">
      Discover exclusive hotel room reservations and luxurious residential suites tailored for your comfort.
    </p>
  </section>

  <!-- Packages Section -->
  <section class="max-w-6xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-10">
    
    <!-- Hotel Room Reservation Card -->
    <div class="package-card bg-white rounded-2xl shadow-xl overflow-hidden transform transition hover:scale-105 hover:shadow-2xl">
      <img src="../images/room1.jpg" alt="Hotel Room" class="w-full h-56 object-cover">
      <div class="p-6">
        <h2 class="text-2xl font-bold text-[#124170] mb-3">Hotel Room Reservation</h2>
        <p class="text-gray-700 mb-5">Book a cozy and premium hotel room for your next getaway. Flexible daily packages available.</p>
        <a href="room_reservation.php?id=<?php echo $customerID; ?>" 
           class="inline-block bg-[#26667F] text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-[#67C090] hover:shadow-lg transition">
          Reserve Now
        </a>
      </div>
    </div>

    <!-- Extra Buttons -->
    <div class="package-card bg-white rounded-2xl shadow-xl overflow-hidden transform transition hover:scale-105 hover:shadow-2xl">
      <img src="../images/room2.jpg" alt="Page 1" class="w-full h-56 object-cover">
      <div class="p-6">
        <h2 class="text-2xl font-bold text-[#124170] mb-3">Current Reservations</h2>
        <p class="text-gray-700 mb-5">Manage your current reservations. View and update all active bookings quickly and easily.</p>
        <a href="current_reservations.php?id=<?php echo $customerID; ?>" 
           class="inline-block bg-[#26667F] text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-[#67C090] hover:shadow-lg transition">
          See Bookings
        </a>
      </div>
    </div>

  </section>

  <!-- Footer -->
  <footer class="bg-[#124170] text-white text-center py-6 mt-12">
    <p class="mb-2">© <?php echo date("Y"); ?> Crystal Heaven. All rights reserved.</p>
  </footer>

  <!-- External JS File -->
  <script src="../js/hotel_packages.js"></script>
</body>
</html>
