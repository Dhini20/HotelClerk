<?php
// Connect to database
include '../db/connection.php';

// Get adminID from URL
$adminID = isset($_GET['adminID']) ? intval($_GET['adminID']) : 0;

// Fetch pending reservations (is_verified = 0), join customer for display
$stmt = $conn->prepare("
    SELECT r.*, c.FirstName, c.LastName, c.Email
    FROM Reservation r
    LEFT JOIN Customer c ON r.CustomerID = c.CustomerID
    WHERE r.is_verified = 0
    ORDER BY r.CreatedDate DESC
");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pending Reservations</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --mint: #DDF4E7;
      --teal: #67C090;
      --deep: #26667F;
      --navy: #124170;
    }
    body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; background: #f6fbfa; color: #0b2540; }
    .card { background: white; border: 1px solid rgba(18,65,112,0.06); box-shadow: 0 8px 28px rgba(6, 40, 60, 0.06); }
    .reveal { opacity:0; transform: translateY(16px); transition: all .6s ease; }
    .reveal.show { opacity:1; transform: translateY(0); }
  </style>
</head>
<body class="min-h-screen">

  <!-- Top bar -->
  <header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-lg bg-[var(--navy)] grid place-items-center text-white font-bold">🏨</div>
        <div>
          <div class="text-lg font-extrabold">Pending Reservations</div>
          <div class="text-sm text-slate-500">Verify incoming reservations</div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="admin_dashboard.php?adminID=<?php echo $adminID; ?>" class="px-4 py-2 rounded-full bg-gradient-to-r from-[var(--teal)] to-[var(--deep)] text-white font-semibold shadow hover:scale-105 transition">
          ← Dashboard
        </a>
      </div>
    </div>
  </header>

  <!-- Loader -->
  <div id="pageLoader" class="fixed inset-0 z-50 grid place-items-center bg-white">
    <div class="flex flex-col items-center gap-4">
      <div class="w-16 h-16 rounded-full border-4 border-[var(--deep)] border-t-[var(--teal)] animate-spin"></div>
      <div class="text-sm text-slate-600">Loading reservations...</div>
    </div>
  </div>

  <main class="max-w-6xl mx-auto px-6 py-10">
    <?php if (count($reservations) === 0): ?>
      <div class="card p-8 rounded-2xl text-center">
        <svg class="mx-auto mb-4 w-16 h-16 text-[var(--teal)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"></path></svg>
        <h2 class="text-2xl font-bold mb-2">No pending reservations</h2>
        <p class="text-slate-600">All reservations are verified or none are waiting at the moment.</p>
      </div>
    <?php else: ?>
      <section id="grid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($reservations as $r): ?>
          <article class="reveal card rounded-2xl p-5 hover:scale-105 transition" data-id="<?php echo $r['ReservationID']; ?>">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-lg font-semibold mb-1">Reservation #<?php echo $r['ReservationID']; ?></h3>
                <div class="text-sm text-slate-500">Guest: <?php echo htmlspecialchars(trim($r['FirstName'].' '.$r['LastName']) ?: 'Guest'); ?></div>
                <div class="text-sm text-slate-500">Email: <?php echo htmlspecialchars($r['Email'] ?? '—'); ?></div>
              </div>
              <div class="text-xs text-orange-500 font-semibold">Pending</div>
            </div>

            <div class="mt-4 text-sm text-slate-600 space-y-1">
              <div><strong>Check-in:</strong> <?php echo htmlspecialchars($r['CheckIn']); ?></div>
              <div><strong>Check-out:</strong> <?php echo htmlspecialchars($r['CheckOut']); ?></div>
              <div><strong>Guests:</strong> <?php echo intval($r['NumGuests']); ?> • <strong>Beds:</strong> <?php echo intval($r['BedCount']); ?></div>
              <div><strong>Created:</strong> <?php echo htmlspecialchars($r['CreatedDate']); ?></div>
              <div><strong>Total Fee:</strong> USD <?php echo number_format((float)$r['Total_Fee'],2); ?></div>
            </div>

            <div class="mt-5 flex gap-3">
              <a href="reservation_details.php?reservationID=<?php echo $r['ReservationID']; ?>&adminID=<?php echo $adminID; ?>"
                 class="flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-[var(--teal)] to-[var(--deep)] text-white font-semibold text-center hover:scale-105 transition">
                 View Details
              </a>

              <button class="quick-verify px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition" data-id="<?php echo $r['ReservationID']; ?>">
                Quick Verify
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>
  </main>

  <!-- Feather -->
  <script src="https://unpkg.com/feather-icons"></script>
  <script>feather.replace()</script>

  <!-- External JS -->
  <script src="../js/pending_reservations.js"></script>
</body>
</html>
