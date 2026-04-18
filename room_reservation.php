<?php
// Connect to database
include '../db/connection.php';

// Capture CustomerID from login redirect
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Customer ID not found. Please login again.");
}
$customerID = intval($_GET['id']);

// Fetch optional charges from database for display
$optionalCharges = $conn->query("SELECT * FROM OptionalCharges")->fetchAll(PDO::FETCH_ASSOC);

// Handle booking form submission
if (isset($_POST['book'])) {
    $checkIn = $_POST['checkin'];
    $checkOut = $_POST['checkout'];
    $numGuests = intval($_POST['numguests']);
    $bedCount = intval($_POST['bedcount']);
    $status = 'Pending';

    // Get total fee from hidden input
    $totalFee = isset($_POST['total_fee']) ? floatval($_POST['total_fee']) : 0;

    // Insert into Reservation table with Total_Fee
    $stmt = $conn->prepare("INSERT INTO Reservation (CheckIn, CheckOut, NumGuests, BedCount, Status, CustomerID, Total_Fee) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$checkIn, $checkOut, $numGuests, $bedCount, $status, $customerID, $totalFee]);

    $reservationID = $conn->lastInsertId();

    // Insert selected optional charges
    if (!empty($_POST['optional_charges'])) {
        $charges = $_POST['optional_charges'];
        $stmtCharge = $conn->prepare("INSERT INTO ReservationOptionalCharges (ReservationID, ChargeID) VALUES (?, ?)");
        foreach ($charges as $chargeID) {
            $stmtCharge->execute([$reservationID, intval($chargeID)]);
        }
    }

    // Redirect to payment page
    header("Location: stripe-demo/index.php?reservationID=$reservationID&customerID=$customerID");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Room Reservation</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #DDF4E7 0%, #67C090 35%, #26667F 70%, #124170 100%); }
  </style>
</head>
<body class="text-gray-900">

<!-- Navbar -->
<nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md shadow-md z-50">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <div class="text-2xl font-bold text-[#124170]">Crystal Heaven</div>
    <ul class="flex space-x-8 text-[#26667F] font-semibold">
      <li><a href="home.php" class="hover:text-[#67C090] transition">Home</a></li>
      <li><a href="customer_reservation.php?id=<?php echo $customerID; ?>" class="hover:text-[#67C090] transition">Packages</a></li>
    </ul>
  </div>
</nav>

<!-- Hero Section -->
<section class="pt-32 pb-12 text-center text-white relative">
  <h1 class="text-4xl md:text-6xl font-bold drop-shadow-lg mb-4 animate-fadeIn">Reserve Your Perfect Room</h1>
  <p class="text-lg md:text-xl max-w-2xl mx-auto opacity-90 animate-slideUp">
    Fill in your details below and book your dream stay with us.
  </p>
</section>

<!-- Booking Form Section -->
<section class="max-w-4xl mx-auto px-6 py-12 bg-white/80 rounded-3xl shadow-2xl backdrop-blur-lg">
  <form method="POST" id="reservationForm">
    <div class="grid md:grid-cols-2 gap-6 mb-6">
      <div class="flex flex-col">
        <label class="mb-2 font-semibold text-[#124170]" for="checkin">Check-In Date</label>
        <input type="date" name="checkin" id="checkin" required
          class="rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-[#67C090] transition">
      </div>
      <div class="flex flex-col">
        <label class="mb-2 font-semibold text-[#124170]" for="checkout">Check-Out Date</label>
        <input type="date" name="checkout" id="checkout" required
          class="rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-[#67C090] transition">
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-6">
      <div class="flex flex-col">
        <label class="mb-2 font-semibold text-[#124170]" for="numguests">Number of Guests</label>
        <input type="number" name="numguests" id="numguests" min="1" value="1" required
          class="rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-[#67C090] transition">
      </div>
      <div class="flex flex-col">
        <label class="mb-2 font-semibold text-[#124170]" for="bedcount">Bed Count</label>
        <input type="number" name="bedcount" id="bedcount" min="1" value="1" required
          class="rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-[#67C090] transition">
      </div>
    </div>

    <div class="mb-6">
      <label class="mb-2 font-semibold text-[#124170]">Optional Charges</label>
      <div class="grid md:grid-cols-2 gap-4">
        <?php foreach($optionalCharges as $charge): ?>
          <label class="flex items-center space-x-3 p-3 bg-gray-100 rounded-xl hover:shadow-lg transition cursor-pointer">
            <!-- value stores Amount, data-chargeid stores ID -->
            <input type="checkbox" name="optional_charges[]" value="<?php echo $charge['ChargeID']; ?>" data-amount="<?php echo $charge['Amount']; ?>" class="form-checkbox h-5 w-5 text-[#67C090]">
            <span><?php echo htmlspecialchars($charge['Description'] . " (USD " . $charge['Amount'] . ")"); ?></span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="mb-6 flex items-center justify-between">
      <button type="button" id="calculateFee" class="bg-[#26667F] text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-[#67C090] hover:shadow-lg transition">
        Calculate Fee
      </button>
      <span class="text-xl font-bold text-[#124170]">Total Fee: USD <span id="totalFee">0</span></span>
    </div>

    <input type="hidden" name="total_fee" id="hiddenTotalFee" value="0">

    <div class="text-center">
      <button type="submit" name="book" class="bg-[#124170] text-white px-8 py-3 rounded-2xl font-bold shadow-lg hover:bg-[#67C090] hover:shadow-xl transition">
        Book
      </button>
    </div>
  </form>
</section>

<!-- Footer -->
<footer class="bg-[#124170] text-white text-center py-6 mt-12">
  <p class="mb-2">© <?php echo date("Y"); ?> Crystal Heaven. All rights reserved.</p>
</footer>

<!-- External JS File -->
<script src="../js/hotel_packages1.js"></script>
</body>
</html>
