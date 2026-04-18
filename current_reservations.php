<?php
// Connect to database
include '../db/connection.php';

// Capture CustomerID safely
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Customer ID not found. Please login again.");
}
$customerID = intval($_GET['id']);

// Fetch reservations for this customer
$stmt = $conn->prepare("SELECT * FROM Reservation WHERE CustomerID = :cid ORDER BY CreatedDate DESC");
$stmt->execute([':cid' => $customerID]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Reservations</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #DDF4E7, #67C090, #26667F, #124170);
      background-attachment: fixed;
    }
  </style>
</head>
<body class="text-gray-900">

  <!-- Navbar -->
  <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md shadow-md z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="text-2xl font-bold text-[#124170]">Crystal Heaven Reservations</div>
      <ul class="flex space-x-8 text-[#26667F] font-semibold">
        <li><a href="home.php" class="hover:text-[#67C090] transition">Home</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero -->
  <section class="pt-32 pb-10 text-center text-white relative">
    <h1 class="text-4xl md:text-6xl font-bold drop-shadow-lg mb-4">Your Current Reservations</h1>
    <p class="text-lg opacity-90">Manage, update, or cancel your active reservations.</p>
  </section>

  <!-- Reservations List -->
  <section class="max-w-6xl mx-auto px-6 py-10 grid gap-6">
    <?php if (count($reservations) > 0): ?>
      <?php foreach ($reservations as $res): ?>
        <div class="reservation-card bg-white rounded-2xl shadow-lg p-6 transform transition hover:scale-105 hover:shadow-2xl">
          <h2 class="text-2xl font-bold text-[#124170] mb-2">Reservation #<?php echo $res['ReservationID']; ?></h2>
          <p><span class="font-semibold">Check-In:</span> <?php echo $res['CheckIn']; ?></p>
          <p><span class="font-semibold">Check-Out:</span> <?php echo $res['CheckOut']; ?></p>
          <p><span class="font-semibold">Guests:</span> <?php echo $res['NumGuests']; ?></p>
          <p><span class="font-semibold">Beds:</span> <?php echo $res['BedCount']; ?></p>
          <p><span class="font-semibold">Status:</span> <span class="text-[#26667F]"><?php echo $res['Status']; ?></span></p>
          <p><span class="font-semibold">Total Fee:</span> USD <?php echo number_format($res['Total_Fee'], 2); ?></p>

          <a href="updateorcancel_reservations.php?res_id=<?php echo $res['ReservationID']; ?>&id=<?php echo $customerID; ?>" 
             class="inline-block mt-4 bg-[#26667F] text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-[#67C090] hover:shadow-lg transition">
            Manage
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-white text-center text-xl">No reservations found.</p>
    <?php endif; ?>
  </section>

  <!-- Footer -->
  <footer class="bg-[#124170] text-white text-center py-6 mt-12">
    <p>© <?php echo date("Y"); ?> Crystal Heaven Reservations. All rights reserved.</p>
  </footer>

  <script src="../js/current_reservations.js"></script>
</body>
</html>
