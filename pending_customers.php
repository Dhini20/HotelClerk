<?php
// Connect to database
include '../db/connection.php';

// Get adminID from URL
$adminID = isset($_GET['adminID']) ? intval($_GET['adminID']) : 0;

// Fetch pending customers
$stmt = $conn->prepare("SELECT * FROM Customer WHERE is_verified = 0 ORDER BY CustomerID DESC");
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pending Customers — Admin</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>

  <style>
    :root {
      --mint: #DDF4E7;
      --teal: #67C090;
      --deep: #26667F;
      --navy: #124170;
    }
    body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; background: linear-gradient(180deg,#08121b 0%, #0b1f2f 60%); color: #E8F8F2; }
    .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.04); }
    .card-hover:hover { transform: translateY(-6px) scale(1.01); box-shadow: 0 18px 40px rgba(6, 95, 70, 0.18); }
    .fade-in-up { transform: translateY(18px); opacity: 0; transition: transform .6s ease, opacity .6s ease; }
    .fade-in-up.show { transform: translateY(0); opacity: 1; }
  </style>
</head>
<body class="antialiased">

  <!-- Top bar -->
  <header class="sticky top-0 z-40 backdrop-blur bg-white/5 border-b border-white/6">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-lg bg-[var(--navy)] grid place-items-center">
          <i data-feather="users" class="w-6 h-6 text-white"></i>
        </div>
        <div>
          <div class="text-lg font-extrabold">Pending Customers</div>
          <div class="text-xs text-white/60">Admin control area</div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="admin_dashboard.php?adminID=<?php echo $adminID; ?>"
           class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-[var(--teal)] to-[var(--deep)] text-navy font-semibold hover:scale-105 transition shadow">
           ← Dashboard
        </a>
      </div>
    </div>
  </header>

  <!-- Page content -->
  <main class="max-w-6xl mx-auto px-6 py-10">
    <?php if (count($customers) === 0): ?>
      <div class="glass p-10 rounded-2xl text-center">
        <svg class="mx-auto mb-4 w-16 h-16 text-[var(--teal)]" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 12l2 2 4-4"/></svg>
        <h2 class="text-2xl font-bold mb-2">No pending customers</h2>
        <p class="text-white/70">All registrations are verified or there are no new signups.</p>
      </div>
    <?php else: ?>
      <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-6" id="customersGrid">
        <?php foreach ($customers as $c): ?>
          <article class="customer-card fade-in-up card-hover glass p-5 rounded-2xl transition cursor-pointer relative" data-id="<?php echo $c['CustomerID']; ?>">
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-[var(--navy)] grid place-items-center text-white font-semibold"><?php echo strtoupper(substr($c['FirstName'] ?: 'G',0,1)); ?></div>
                <div>
                  <h3 class="text-lg font-bold leading-tight"><?php echo htmlspecialchars($c['FirstName'].' '.$c['LastName']); ?></h3>
                  <div class="text-xs text-white/60"><?php echo htmlspecialchars($c['Username']); ?></div>
                </div>
              </div>
              <div class="text-sm text-yellow-400 font-semibold">Pending</div>
            </div>

            <div class="text-sm text-white/70 mb-4">
              <div class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($c['Email']); ?></div>
              <div class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($c['PhoneNo']); ?></div>
            </div>

            <div class="flex gap-3">
              <button class="view-btn flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-[var(--teal)] to-[var(--deep)] text-navy font-semibold hover:scale-105 transition"
                      data-id="<?php echo $c['CustomerID']; ?>">
                View Details
              </button>
              <button class="quick-verify px-4 py-2 rounded-lg border border-white/10 text-white/90 hover:bg-white/5 transition" data-id="<?php echo $c['CustomerID']; ?>">
                Quick Verify
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>
  </main>

  <!-- Modal (hidden) -->
  <div id="customerModal" class="fixed inset-0 bg-black/65 hidden items-center justify-center z-50 px-4">
    <div class="bg-[#0f172a] w-full max-w-2xl rounded-2xl p-6 shadow-2xl transform scale-95 opacity-0 transition">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h2 id="modalName" class="text-2xl font-bold"></h2>
          <div id="modalUsername" class="text-sm text-white/60"></div>
        </div>
        <button id="modalClose" class="text-white/60 hover:text-red-400">
          <i data-feather="x" class="w-6 h-6"></i>
        </button>
      </div>

      <div id="modalBody" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white/80">
        <div>
          <p><strong>NIC</strong><br><span id="modalNIC" class="text-white"></span></p>
          <p class="mt-3"><strong>Phone</strong><br><span id="modalPhone" class="text-white"></span></p>
        </div>
        <div>
          <p><strong>Email</strong><br><span id="modalEmail" class="text-white"></span></p>
          <p class="mt-3"><strong>Address</strong><br><span id="modalAddress" class="text-white"></span></p>
        </div>
      </div>

      <div class="mt-6 flex gap-3">
        <button id="modalVerify" class="flex-1 px-5 py-3 rounded-full bg-gradient-to-r from-[var(--teal)] to-[var(--deep)] text-navy font-bold hover:scale-105 transition">
          Verify
        </button>
        <button id="modalCancel" class="px-5 py-3 rounded-full bg-white/5 hover:bg-white/10 transition">Close</button>
      </div>
    </div>
  </div>

  <!-- Toast container -->
  <div id="toastContainer" class="fixed bottom-6 right-6 space-y-2 z-60"></div>

  <!-- Scripts -->
  <script>
    // ensure feather icons exist soon
    document.addEventListener('DOMContentLoaded', () => feather.replace());
  </script>

  <script src="../js/pending_customers.js" defer></script>
</body>
</html>
