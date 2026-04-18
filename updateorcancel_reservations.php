<?php
// Connect to database
include '../db/connection.php';

// Capture reservation ID + customer ID
if (!isset($_GET['res_id']) || !isset($_GET['id'])) {
    die("Invalid request.");
}
$reservationID = intval($_GET['res_id']);
$customerID = intval($_GET['id']);

// Handle form submission (Update or Cancel)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        $stmt = $conn->prepare("UPDATE Reservation SET Status = 'Cancelled' WHERE ReservationID = :rid AND CustomerID = :cid");
        $stmt->execute([':rid' => $reservationID, ':cid' => $customerID]);
        header("Location: current_reservations.php?id=$customerID");
        exit;
    }
    if (isset($_POST['update'])) {
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $guests = intval($_POST['numguests']);
        $beds = intval($_POST['bedcount']);
        $totalFee = floatval($_POST['totalfee']);

        $stmt = $conn->prepare("UPDATE Reservation 
            SET CheckIn = :cin, CheckOut = :cout, NumGuests = :guests, BedCount = :beds, Total_Fee = :fee
            WHERE ReservationID = :rid AND CustomerID = :cid AND Status != 'Cancelled'");
        $stmt->execute([
            ':cin' => $checkin,
            ':cout' => $checkout,
            ':guests' => $guests,
            ':beds' => $beds,
            ':fee' => $totalFee,
            ':rid' => $reservationID,
            ':cid' => $customerID
        ]);
        header("Location: current_reservations.php?id=$customerID");
        exit;
    }
}

// Fetch reservation details
$stmt = $conn->prepare("SELECT * FROM Reservation WHERE ReservationID = :rid AND CustomerID = :cid");
$stmt->execute([':rid' => $reservationID, ':cid' => $customerID]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$res) die("Reservation not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Reservation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#DDF4E7] font-sans">

  <div class="max-w-3xl mx-auto mt-20 bg-white rounded-2xl shadow-lg p-8">
    <h1 class="text-3xl font-bold text-[#124170] mb-6">Manage Reservation #<?php echo $res['ReservationID']; ?></h1>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block font-semibold">Check-In</label>
        <input type="date" name="checkin" value="<?php echo $res['CheckIn']; ?>" class="w-full p-3 rounded-lg border shadow-sm">
      </div>
      <div>
        <label class="block font-semibold">Check-Out</label>
        <input type="date" name="checkout" value="<?php echo $res['CheckOut']; ?>" class="w-full p-3 rounded-lg border shadow-sm">
      </div>
      <div>
        <label class="block font-semibold">Guests</label>
        <input type="number" name="numguests" value="<?php echo $res['NumGuests']; ?>" class="w-full p-3 rounded-lg border shadow-sm">
      </div>
      <div>
        <label class="block font-semibold">Beds</label>
        <input type="number" name="bedcount" value="<?php echo $res['BedCount']; ?>" class="w-full p-3 rounded-lg border shadow-sm">
      </div>
      <div>
        <label class="block font-semibold">Total Fee (USD)</label>
        <input type="number" step="0.01" name="totalfee" value="<?php echo $res['Total_Fee']; ?>" class="w-full p-3 rounded-lg border shadow-sm">
      </div>

      <div class="flex justify-between mt-6">
        <button type="submit" name="update" class="bg-[#26667F] text-white px-6 py-3 rounded-lg hover:bg-[#67C090] transition shadow-md">
          Update Reservation
        </button>
        <button type="submit" name="cancel" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-800 transition shadow-md">
          Cancel Reservation
        </button>
      </div>
    </form>
  </div>

  <script src="../js/updateorcancel_reservations.js"></script>
</body>
</html>
