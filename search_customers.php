<?php
// safer include regardless of where this file sits
include __DIR__ . '/../db/connection.php';

header('Content-Type: text/html; charset=UTF-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    $stmt = $conn->prepare("SELECT * FROM Customer WHERE is_verified = 1 ORDER BY CustomerID DESC");
    $stmt->execute();
} else {
    $stmt = $conn->prepare("SELECT * FROM Customer WHERE is_verified = 1 
                            AND (NIC LIKE ? OR FirstName LIKE ? OR LastName LIKE ? OR PhoneNo LIKE ? OR Email LIKE ?)
                            ORDER BY CustomerID DESC");
    $like = "%$q%";
    $stmt->execute([$like, $like, $like, $like, $like]);
}

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$customers || count($customers) === 0) {
    echo '<div class="glass p-10 rounded-2xl text-center col-span-full text-white/60">No matching customers found.</div>';
    exit;
}

foreach ($customers as $c): ?>
  <article class="customer-card fade-in-up card-hover glass p-5 rounded-2xl transition cursor-pointer relative" data-id="<?php echo $c['CustomerID']; ?>">
    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-lg bg-[#124170] grid place-items-center text-white font-semibold">
          <?php echo strtoupper(substr($c['FirstName'] ?: 'C', 0, 1)); ?>
        </div>
        <div>
          <h3 class="text-lg font-bold leading-tight"><?php echo htmlspecialchars(($c['FirstName'] ?? '').' '.($c['LastName'] ?? '')); ?></h3>
          <div class="text-xs text-white/60"><?php echo htmlspecialchars($c['Username'] ?? ''); ?></div>
        </div>
      </div>
      <div class="text-sm text-green-400 font-semibold">Verified</div>
    </div>

    <div class="text-sm text-white/70 mb-4">
      <div class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($c['Email'] ?? ''); ?></div>
      <div class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($c['PhoneNo'] ?? ''); ?></div>
    </div>

    <div class="flex gap-3">
      <button class="view-btn flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-[#67C090] to-[#26667F] text-navy font-semibold hover:scale-105 transition"
              data-id="<?php echo $c['CustomerID']; ?>">View Details</button>
      <button class="delete-btn px-4 py-2 rounded-lg border border-red-500/50 text-red-400 hover:bg-red-500/10 transition"
              data-id="<?php echo $c['CustomerID']; ?>">Delete</button>
    </div>
  </article>
<?php endforeach; ?>
