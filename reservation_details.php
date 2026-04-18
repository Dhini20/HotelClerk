<?php
// Connect to database
include '../db/connection.php';

// Get adminID & reservationID
$adminID = isset($_GET['adminID']) ? intval($_GET['adminID']) : 0;
$reservationID = isset($_GET['reservationID']) ? intval($_GET['reservationID']) : 0;

if (!$reservationID) {
    die("Reservation ID missing.");
}

// fetch reservation + customer
$stmt = $conn->prepare("
  SELECT r.*, c.FirstName, c.LastName, c.Email, c.PhoneNo
  FROM Reservation r
  LEFT JOIN Customer c ON r.CustomerID = c.CustomerID
  WHERE r.ReservationID = :rid
  LIMIT 1
");
$stmt->execute([':rid' => $reservationID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) die("Reservation not found.");

// Handle POST verify
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
    $u = $conn->prepare("UPDATE Reservation SET is_verified = 1 WHERE ReservationID = ?");
    $u->execute([$reservationID]);
    header("Location: pending_reservations.php?adminID={$adminID}&msg=verified");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reservation #<?php echo $reservationID; ?> — Details</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    :root { --mint:#DDF4E7; --teal:#67C090; --deep:#26667F; --navy:#124170; }
    body{font-family:Inter,system-ui; background:#f6fbfa; color:#0b2540}
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

  <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl p-8">
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-2xl font-extrabold">Reservation #<?php echo $reservationID; ?></h1>
        <div class="text-sm text-slate-500">Created: <?php echo htmlspecialchars($row['CreatedDate']); ?></div>
      </div>
      <a href="pending_reservations.php?adminID=<?php echo $adminID; ?>" class="text-sm text-slate-600 hover:underline">← Back</a>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <div class="space-y-3">
        <div><strong>Guest:</strong> <?php echo htmlspecialchars(trim($row['FirstName'].' '.$row['LastName']) ?: 'Guest'); ?></div>
        <div><strong>Email:</strong> <?php echo htmlspecialchars($row['Email'] ?? '—'); ?></div>
        <div><strong>Phone:</strong> <?php echo htmlspecialchars($row['PhoneNo'] ?? '—'); ?></div>
        <div><strong>Check-in:</strong> <?php echo htmlspecialchars($row['CheckIn']); ?></div>
        <div><strong>Check-out:</strong> <?php echo htmlspecialchars($row['CheckOut']); ?></div>
      </div>

      <div class="space-y-3">
        <div><strong>Guests:</strong> <?php echo intval($row['NumGuests']); ?></div>
        <div><strong>Beds:</strong> <?php echo intval($row['BedCount']); ?></div>
        <div><strong>Status:</strong> <?php echo htmlspecialchars($row['Status']); ?></div>
        <div><strong>Total Fee:</strong> USD <?php echo number_format((float)$row['Total_Fee'],2); ?></div>
      </div>
    </div>

    <div class="mt-6 flex gap-3">
      <?php if (intval($row['is_verified']) === 0): ?>
        <form method="POST" class="flex-1">
          <button name="verify" class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-[var(--teal)] to-[var(--deep)] text-white font-bold hover:scale-105 transition">Verify Reservation</button>
        </form>
      <?php else: ?>
        <div class="px-6 py-3 rounded-lg bg-green-100 text-green-800 font-semibold">Already Verified</div>
      <?php endif; ?>

      <a href="admin_dashboard.php?adminID=<?php echo $adminID; ?>" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition">Dashboard</a>
    </div>
  </div>

</body>
</html>
