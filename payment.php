<?php
// Connect to database
include '../db/connection.php';

// Require reservationID and customerID via GET (from previous step)
if (!isset($_GET['reservationID']) || !isset($_GET['customerID'])) {
    die("Reservation information not found. Please return to packages or bookings.");
}
$reservationID = intval($_GET['reservationID']);
$customerID    = intval($_GET['customerID']);

// Fetch reservation and selected optional charges for summary display
$stmt = $conn->prepare("SELECT * FROM Reservation WHERE ReservationID = ? AND CustomerID = ?");
$stmt->execute([$reservationID, $customerID]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$reservation) {
    die("Reservation not found or does not belong to this customer.");
}

$totalFee = isset($reservation['Total_Fee']) ? $reservation['Total_Fee'] : 0;

// Get selected optional charges for the reservation (if any)
$stmt2 = $conn->prepare("
    SELECT oc.Description, oc.Amount
    FROM ReservationOptionalCharges roc
    JOIN OptionalCharges oc ON roc.ChargeID = oc.ChargeID
    WHERE roc.ReservationID = ?
");
$stmt2->execute([$reservationID]);
$selectedCharges = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Error and success handling variables
$errors = [];
$infoMessage = "";

// Handle Save (payment submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    // Basic server-side sanitization and validation
    $cardholder = trim($_POST['cardholder'] ?? '');
    $cardnumber = trim($_POST['cardnumber'] ?? '');
    $cardtype   = trim($_POST['cardtype'] ?? '');
    $expmonth   = intval($_POST['expmonth'] ?? 0);
    $expyear    = intval($_POST['expyear'] ?? 0);
    $cvv        = trim($_POST['cvv'] ?? '');

    // Normalize card number digits only
    $cardDigits = preg_replace('/\D/', '', $cardnumber);

    // Validate
    if ($cardholder === '') {
        $errors[] = "Cardholder name is required.";
    }
    if ($cardDigits === '' || strlen($cardDigits) < 13 || strlen($cardDigits) > 19) {
        $errors[] = "Card number must be between 13 and 19 digits.";
    }
    $allowedTypes = ['Visa', 'MasterCard', 'American Express'];
    if (!in_array($cardtype, $allowedTypes, true)) {
        $errors[] = "Invalid card type selected.";
    }
    if ($expmonth < 1 || $expmonth > 12) {
        $errors[] = "Expiration month must be between 1 and 12.";
    }
    $currentYear = intval(date("Y"));
    if ($expyear < $currentYear || $expyear > $currentYear + 20) {
        $errors[] = "Expiration year is invalid.";
    }
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = "CVV must be 3 or 4 digits.";
    }

    // Prevent duplicate payment if payment_status already 1
    if (intval($reservation['payment_status']) === 1) {
        $errors[] = "Payment for this reservation has already been made.";
    }

    // If no errors, insert into Payment and update reservation
    if (empty($errors)) {
        try {
            $conn->beginTransaction();

            $insert = $conn->prepare("
                INSERT INTO Payment
                (CardholderName, CardNumber, CardType, ExpirationMonth, ExpirationYear, CVV, ReservationID)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->execute([
                $cardholder,
                $cardDigits,
                $cardtype,
                $expmonth,
                $expyear,
                $cvv,
                $reservationID
            ]);
            $paymentID = $conn->lastInsertId();

            $update = $conn->prepare("UPDATE Reservation SET payment_status = 1 WHERE ReservationID = ?");
            $update->execute([$reservationID]);

            $conn->commit();

            // Redirect to home (or a success/receipt page). Pass customer id and payment flag.
            header("Location: home.php?id={$customerID}&payment=success");
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            $errors[] = "Database error while saving payment: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Complete Payment - Hotel Bliss</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #DDF4E7 0%, #67C090 35%, #26667F 70%, #124170 100%);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }
    /* small decorative floating shapes */
    .float-shape { position: absolute; border-radius: 9999px; opacity: .12; pointer-events: none; transform: translate3d(0,0,0); }
    .card-number-mask { letter-spacing: 3px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", "Courier New", monospace; }
  </style>
</head>
<body class="text-gray-900">

  <!-- Decorative floating shapes -->
  <div id="shape1" class="float-shape bg-white w-44 h-44 left-6 top-24"></div>
  <div id="shape2" class="float-shape bg-white w-28 h-28 right-10 top-64"></div>

  <!-- Navbar -->
  <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md shadow-md z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#67C090] to-[#124170] flex items-center justify-center text-white font-bold">HB</div>
        <div class="text-2xl font-bold text-[#124170]">Hotel Bliss</div>
      </div>
      <ul class="hidden md:flex gap-8 text-[#26667F] font-semibold">
        <li><a href="home.php?id=<?php echo $customerID; ?>" class="hover:text-[#67C090] transition">Home</a></li>
        <li><a href="customer_reservation.php?id=<?php echo $customerID; ?>" class="hover:text-[#67C090] transition">Packages</a></li>
        <li><a href="contact.php?id=<?php echo $customerID; ?>" class="hover:text-[#67C090] transition">Contact</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero -->
  <header class="pt-28 pb-8 text-center text-white relative">
    <h1 class="text-4xl md:text-5xl font-extrabold drop-shadow-lg mb-3 animate-fadeIn">Complete Your Payment</h1>
    <p class="text-md md:text-lg max-w-2xl mx-auto opacity-90 animate-slideUp">
      Securely enter payment details to confirm the reservation. Card information is stored for demonstration only.
    </p>
  </header>

  <!-- Main container -->
  <main class="max-w-6xl mx-auto px-6 py-10 grid lg:grid-cols-2 gap-10">

    <!-- Left: Payment Form -->
    <section class="bg-white/95 rounded-3xl shadow-2xl p-6 relative overflow-hidden">
      <?php if (!empty($errors)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">
          <ul class="list-disc pl-5">
            <?php foreach ($errors as $err): ?>
              <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" id="paymentForm" class="space-y-6">
        <!-- Hidden to persist ids on POST -->
        <input type="hidden" name="reservationID" value="<?php echo $reservationID; ?>">
        <input type="hidden" name="customerID" value="<?php echo $customerID; ?>">

        <div class="grid grid-cols-1 gap-4">
          <label class="block">
            <span class="text-sm font-semibold text-[#124170]">Cardholder Name</span>
            <input name="cardholder" id="cardholder" value="<?php echo htmlspecialchars($_POST['cardholder'] ?? ''); ?>"
              class="mt-2 block w-full rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-[#67C090] transition" placeholder="Full name on card" required>
          </label>

          <label class="block">
            <span class="text-sm font-semibold text-[#124170]">Card Number</span>
            <input name="cardnumber" id="cardnumber" inputmode="numeric" autocomplete="cc-number"
              value="<?php echo htmlspecialchars($_POST['cardnumber'] ?? ''); ?>"
              maxlength="19"
              class="mt-2 block w-full rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-[#67C090] transition card-number-mask" placeholder="1234 5678 9012 3456" required>
          </label>

          <div class="grid grid-cols-2 gap-4">
            <label>
              <span class="text-sm font-semibold text-[#124170]">Card Type</span>
              <select name="cardtype" id="cardtype" class="mt-2 block w-full rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-[#67C090] transition" required>
                <option value="">Select card type</option>
                <option <?php if(isset($_POST['cardtype']) && $_POST['cardtype']=='Visa') echo 'selected'; ?>>Visa</option>
                <option <?php if(isset($_POST['cardtype']) && $_POST['cardtype']=='MasterCard') echo 'selected'; ?>>MasterCard</option>
                <option <?php if(isset($_POST['cardtype']) && $_POST['cardtype']=='American Express') echo 'selected'; ?>>American Express</option>
              </select>
            </label>

            <div class="grid grid-cols-3 gap-2 items-end">
              <label>
                <span class="text-sm font-semibold text-[#124170]">Exp Month</span>
                <select name="expmonth" id="expmonth" class="mt-2 block w-full rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-[#67C090] transition" required>
                  <option value="">MM</option>
                  <?php for($m=1;$m<=12;$m++): ?>
                    <option value="<?php echo $m; ?>" <?php if(isset($_POST['expmonth']) && intval($_POST['expmonth'])==$m) echo 'selected'; ?>><?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?></option>
                  <?php endfor; ?>
                </select>
              </label>
              <label>
                <span class="text-sm font-semibold text-[#124170]">Exp Year</span>
                <select name="expyear" id="expyear" class="mt-2 block w-full rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-[#67C090] transition" required>
                  <option value="">YYYY</option>
                  <?php $y = date("Y"); for($i=0;$i<=12;$i++): $yr = $y + $i; ?>
                    <option value="<?php echo $yr; ?>" <?php if(isset($_POST['expyear']) && intval($_POST['expyear'])==$yr) echo 'selected'; ?>><?php echo $yr; ?></option>
                  <?php endfor; ?>
                </select>
              </label>
              <label>
                <span class="text-sm font-semibold text-[#124170]">CVV</span>
                <input name="cvv" id="cvv" maxlength="4" value="<?php echo htmlspecialchars($_POST['cvv'] ?? ''); ?>"
                  class="mt-2 block w-full rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-[#67C090] transition" placeholder="123" inputmode="numeric" required>
              </label>
            </div>
          </div>

        </div>

        <!-- Action buttons -->
        <div class="flex gap-4 items-center">
          <button type="submit" name="save"
            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#124170] text-white px-6 py-3 font-semibold shadow-md hover:scale-105 hover:shadow-xl transition transform">
            Save & Pay
          </button>

          <a href="home.php?id=<?php echo $customerID; ?>"
            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-transparent border border-gray-300 text-[#124170] px-5 py-3 font-semibold hover:bg-[#67C090]/10 transition">
            Skip & Return Home
          </a>

          <div class="ml-auto text-sm text-gray-600">Amount: <span class="font-bold text-[#124170]">Rs <?php echo number_format($totalFee,2); ?></span></div>
        </div>

      </form>
    </section>

    <!-- Right: Summary & Card Preview -->
    <aside class="bg-white/95 rounded-3xl shadow-2xl p-6">
      <div class="mb-6">
        <h3 class="text-lg font-bold text-[#124170]">Reservation Summary</h3>
        <div class="mt-3 text-sm text-gray-700 space-y-2">
          <div><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservationID); ?></div>
          <div><strong>Check-In:</strong> <?php echo htmlspecialchars($reservation['CheckIn']); ?></div>
          <div><strong>Check-Out:</strong> <?php echo htmlspecialchars($reservation['CheckOut']); ?></div>
          <div><strong>Guests:</strong> <?php echo htmlspecialchars($reservation['NumGuests']); ?></div>
          <div><strong>Beds:</strong> <?php echo htmlspecialchars($reservation['BedCount']); ?></div>
          <?php if (!empty($selectedCharges)): ?>
            <div><strong>Optional Charges:</strong>
              <ul class="list-disc pl-5">
                <?php foreach($selectedCharges as $c): ?>
                  <li><?php echo htmlspecialchars($c['Description'] . " - Rs " . number_format($c['Amount'],2)); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <div class="pt-3 border-t mt-3">
            <div class="text-sm text-gray-500">Total Fee</div>
            <div class="text-2xl font-extrabold text-[#26667F]">Rs <?php echo number_format($totalFee,2); ?></div>
          </div>
        </div>
      </div>

      <!-- Live card preview -->
      <div class="mt-6">
        <h4 class="text-md font-semibold text-[#124170] mb-3">Card Preview</h4>
        <div id="cardPreview" class="relative rounded-lg p-6 bg-gradient-to-br from-[#26667F] to-[#124170] text-white shadow-xl transform transition hover:scale-102">
          <div class="flex justify-between items-center mb-6">
            <div class="text-xs uppercase opacity-90">Hotel Bliss</div>
            <div id="previewType" class="text-sm font-semibold opacity-90">--</div>
          </div>
          <div class="text-xl tracking-wider font-mono mb-3" id="previewNumber">•••• •••• •••• ••••</div>
          <div class="flex justify-between items-center text-sm">
            <div>
              <div class="text-xs opacity-80">Cardholder</div>
              <div id="previewName" class="font-semibold">Full Name</div>
            </div>
            <div class="text-right">
              <div class="text-xs opacity-80">Exp</div>
              <div id="previewExp" class="font-semibold">MM / YYYY</div>
            </div>
          </div>
        </div>
      </div>

    </aside>

  </main>

  <!-- Footer -->
  <footer class="bg-[#124170] text-white text-center py-6 mt-12">
    <p class="mb-2">© <?php echo date("Y"); ?> Hotel Bliss. All rights reserved.</p>
    <div class="flex justify-center space-x-6">
      <a href="#" class="hover:text-[#67C090] transition">Facebook</a>
      <a href="#" class="hover:text-[#67C090] transition">Instagram</a>
      <a href="#" class="hover:text-[#67C090] transition">Twitter</a>
    </div>
  </footer>

  <!-- External JS -->
  <script src="../js/payment.js"></script>
</body>
</html>
