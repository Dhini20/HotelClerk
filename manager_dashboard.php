<?php
// Connect to database
include '../db/connection.php';

// Get admin ID from URL
$managerID = isset($_GET['managerID']) ? intval($_GET['managerID']) : 0;

// Fetch statistics
try {
    $totalCustomers = $conn->query("SELECT COUNT(*) FROM Customer")->fetchColumn();
    $verifiedCustomers = $conn->query("SELECT COUNT(*) FROM Customer WHERE is_verified=1")->fetchColumn();
    $pendingCustomers = $conn->query("SELECT COUNT(*) FROM Customer WHERE is_verified=0")->fetchColumn();

    $totalReservations = $conn->query("SELECT COUNT(*) FROM Reservation")->fetchColumn();
    $verifiedReservations = $conn->query("SELECT COUNT(*) FROM Reservation WHERE is_verified=1")->fetchColumn();
    $pendingReservations = $conn->query("SELECT COUNT(*) FROM Reservation WHERE is_verified=0")->fetchColumn();

    $totalRevenue = $conn->query("SELECT SUM(Total_Fee) FROM Reservation WHERE payment_status=1")->fetchColumn();
    $totalRevenue = number_format((float)$totalRevenue,2);

    // New statistics
    $paidReservations = $conn->query("SELECT COUNT(*) FROM Reservation WHERE Status='Paid'")->fetchColumn();
    $cancelledReservations = $conn->query("SELECT COUNT(*) FROM Reservation WHERE Status='Cancelled'")->fetchColumn();

} catch(PDOException $e){
    die("Database Error: ".$e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manager Dashboard - Hotel Reservation System</title>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
body { 
  font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial; 
  background: #DDF4E7; 
  color: #124170; 
}

/* Pulse animation */
.pulse-slow { 
  animation: pulse 1.8s ease-in-out infinite; 
}
@keyframes pulse {
  0%,100% { transform: scale(1); }
  50% { transform: scale(1.04); }
}

/* Reveal animations */
.reveal { 
  opacity: 0; 
  transform: translateY(12px) scale(.995); 
  transition: opacity .7s ease, transform .7s ease; 
}
.reveal.show { 
  opacity: 1; 
  transform: translateY(0) scale(1); 
}

/* Glass effect cards/sections */
.glass { 
  background: rgba(255, 255, 255, 0.6); 
  backdrop-filter: blur(8px); 
  border: 1px solid rgba(18, 65, 112, 0.15); 
  border-radius: 12px; 
}

/* Sidebar hover items */
.sidebar:hover .item { 
  transition: all .3s; 
  transform: scale(1.02); 
  box-shadow: 0 5px 20px rgba(103, 192, 144, 0.3); 
  background-color: #67C090; 
  color: #fff; 
}
</style>

</head>
<body class="antialiased">

<!-- Loader -->
<div id="pageLoader" class="fixed inset-0 z-50 grid place-items-center bg-[#071427]">
  <div class="flex flex-col items-center gap-4">
    <div class="w-20 h-20 rounded-full border-4 border-[#0f3146] border-t-[#67C090] animate-spin"></div>
    <h2 class="text-xl font-bold text-[#DDF4E7]">Loading Dashboard...</h2>
  </div>
</div>

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside id="sidebar" class="w-72 bg-[#081626] glass p-5 flex flex-col justify-between transition-all duration-300">
    <div>
      <div class="flex items-center gap-3 mb-6">
        <div class="w-11 h-11 rounded-lg bg-[#fff] grid place-items-center text-white font-bold">🏨</div>
        <div>
          <div class="text-white font-extrabold">Crystal Heaven</div>
          <div class="text-xs text-white/60">Manager Panel</div>
        </div>
      </div>
<nav class="flex flex-col gap-2 text-white">
  <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#67C090]/80 item transition">
    <i data-feather="pie-chart" class="w-5 h-5"></i> Dashboard
  </a>
  <a href="all_reservations.php?adminID=<?php echo $adminID; ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#67C090]/80 item transition">
    <i data-feather="calendar" class="w-5 h-5"></i> Reservations
  </a>
  <a href="verified_customers.php?adminID=<?php echo $adminID; ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#67C090]/80 item transition">
    <i data-feather="users" class="w-5 h-5"></i> Verified Customers
  </a>
  <a href="pending_customers.php?adminID=<?php echo $adminID; ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#67C090]/80 item transition">
    <i data-feather="credit-card" class="w-5 h-5"></i> Pending Customers
  </a>
  <a href="all_travel_reservations.php?adminID=<?php echo $adminID; ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#67C090]/80 item transition">
    <i data-feather="settings" class="w-5 h-5"></i> Block Bookings
  </a>
</nav>

    </div>
    <div>
      <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 item transition"><i data-feather="log-out" class="w-5 h-5"></i> Logout</a>
    </div>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-6 lg:p-12">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-extrabold tracking-tight">Manager Dashboard</h1>
      <div class="flex items-center gap-4">
        
        <button class="relative p-2 rounded-full hover:bg-white/5 transition">
          <i data-feather="bell"></i>
          <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
      </div>
    </div>

    <!-- Statistic Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-6 reveal">
      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Total Customers</span>
          <i data-feather="users"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $totalCustomers; ?></div>
      </div>

      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Verified Customers</span>
          <i data-feather="check-circle"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $verifiedCustomers; ?></div>
      </div>

      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Pending Customers</span>
          <i data-feather="clock"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $pendingCustomers; ?></div>
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-6 reveal">
      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Total Reservations</span>
          <i data-feather="calendar"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $totalReservations; ?></div>
      </div>

      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Verified Reservations</span>
          <i data-feather="check-circle"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $verifiedReservations; ?></div>
      </div>

      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Pending Reservations</span>
          <i data-feather="clock"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $pendingReservations; ?></div>
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8 reveal">
      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Total Revenue</span>
          <i data-feather="dollar-sign"></i>
        </div>
        <div class="text-2xl font-bold">$<?php echo $totalRevenue; ?></div>
      </div>

      <!-- Paid Reservations -->
      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Paid Reservations</span>
          <i data-feather="check-circle"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $paidReservations; ?></div>
      </div>

      <!-- Cancelled Reservations -->
      <div class="glass p-5 rounded-2xl hover:scale-105 transition">
        <div class="flex justify-between items-center mb-2">
          <span>Cancelled Reservations</span>
          <i data-feather="x-circle"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $cancelledReservations; ?></div>
      </div>
    </div>

    <!-- Buttons Section -->
    <div class="flex flex-wrap gap-4 reveal">
      <a href="financial_info.php?managerID=<?php echo $managerID; ?>" class="px-6 py-3 rounded-full bg-gradient-to-r from-[#67C090] to-[#26667F] text-white font-bold hover:scale-105 transition">Financial Information</a>


    </div>
  </main>
</div>a

<!-- Feather icons -->
<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace()</script>

<!-- External JS -->
<script src="../js/admin_dashboard.js"></script>
</body>
</html>
