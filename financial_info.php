<?php
// Connect to database
include '../db/connection.php';

// Initialize variables
$year = isset($_GET['year']) ? trim($_GET['year']) : '';
$month = isset($_GET['month']) ? trim($_GET['month']) : '';
$day = isset($_GET['day']) ? trim($_GET['day']) : '';
$error = '';
$reservations = [];
$totalRevenue = 0.00;

// Build SQL dynamically
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['filter']))) {
    if ($month && !$year) {
        $error = "Please select a year when filtering by month.";
    } elseif ($day && !$year) {
        $error = "Please select a year when filtering by date.";
    } else {
        $sql = "SELECT CheckIn, CheckOut, Total_Fee FROM Reservation WHERE 1=1";
        $params = [];

        if ($year && !$month && !$day) {
            $sql .= " AND YEAR(CheckOut) = :year";
            $params[':year'] = $year;
        } elseif ($year && $month && !$day) {
            $sql .= " AND YEAR(CheckOut) = :year AND MONTH(CheckOut) = :month";
            $params[':year'] = $year;
            $params[':month'] = $month;
        } elseif ($year && $month && $day) {
            $sql .= " AND CheckOut = :date";
            $params[':date'] = "$year-$month-$day";
        }

        $sql .= " ORDER BY CheckOut DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reservations as $r) {
            $totalRevenue += (float)$r['Total_Fee'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Financial Information — Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --mint: #DDF4E7;
      --teal: #67C090;
      --deep: #26667F;
      --navy: #124170;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fafb;
      color: var(--navy);
    }
    .card {
      background: var(--mint);
      border-radius: 12px;
      padding: 1.5rem;
      transition: all 0.3s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .btn {
      background: var(--teal);
      color: #fff;
      padding: .75rem 1.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn:hover {
      background: var(--deep);
      transform: scale(1.03);
      box-shadow: 0 8px 20px rgba(18,65,112,0.2);
    }
    .error-msg {
      background-color: #ffe0e0;
      color: #a10000;
      border-left: 5px solid #a10000;
      padding: 10px 15px;
      border-radius: 6px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-gradient-to-r from-[var(--mint)] via-[var(--teal)] to-[var(--deep)] py-6 shadow-lg">
    <h1 class="text-center text-4xl font-bold text-white drop-shadow-lg animate-fadeIn">
      Financial Dashboard
    </h1>
  </header>

  <main class="flex-grow container mx-auto px-6 py-10 space-y-8">

    <!-- Filter Section -->
    <section class="card animate-fadeUp">
      <h2 class="text-2xl font-semibold mb-4">Filter Revenue</h2>

      <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
          <label class="font-medium text-[var(--navy)]">Year</label>
          <input type="number" name="year" min="2000" max="2099" value="<?= htmlspecialchars($year) ?>"
            placeholder="YYYY"
            class="border border-[var(--deep)] rounded-lg p-2 w-full">
        </div>
        <div>
          <label class="font-medium text-[var(--navy)]">Month</label>
          <input type="number" name="month" min="1" max="12" value="<?= htmlspecialchars($month) ?>"
            placeholder="MM"
            class="border border-[var(--deep)] rounded-lg p-2 w-full">
        </div>
        <div>
          <label class="font-medium text-[var(--navy)]">Date</label>
          <input type="number" name="day" min="1" max="31" value="<?= htmlspecialchars($day) ?>"
            placeholder="DD"
            class="border border-[var(--deep)] rounded-lg p-2 w-full">
        </div>
        <div>
          <button type="submit" name="filter" class="btn w-full">Apply Filter</button>
        </div>
      </form>
    </section>

    <!-- Summary Card -->
    <?php if (!$error && ($_SERVER['REQUEST_METHOD'] === 'GET') && isset($_GET['filter'])): ?>
    <section class="card animate-fadeUp">
      <h2 class="text-2xl font-semibold mb-2">Total Revenue</h2>
      <p class="text-4xl font-bold text-[var(--deep)]">
        USD <?= number_format($totalRevenue, 2) ?>
      </p>
      <p class="text-sm text-[var(--deep)]/70 mt-1">
        Showing results for 
        <?php
          if ($year && !$month && !$day) echo "Year $year";
          elseif ($year && $month && !$day) echo "Month $month of $year";
          elseif ($year && $month && $day) echo "Date $year-$month-$day";
        ?>
      </p>
    </section>
    <?php endif; ?>

    <!-- Table of Reservations -->
    <?php if (!$error && count($reservations) > 0): ?>
    <section class="card animate-fadeUp overflow-x-auto">
      <h2 class="text-2xl font-semibold mb-4">Reservations</h2>
      <table class="w-full table-auto min-w-[600px] border-separate border-spacing-y-3">
        <thead class="bg-[var(--teal)] text-white">
          <tr>
            <th class="px-4 py-2 text-left">Check-In</th>
            <th class="px-4 py-2 text-left">Check-Out</th>
            <th class="px-4 py-2 text-right">Total Fee (USD)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reservations as $r): ?>
            <tr class="bg-white hover:bg-[var(--mint)] transition transform hover:scale-101">
              <td class="px-4 py-2"><?= htmlspecialchars($r['CheckIn']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($r['CheckOut']) ?></td>
              <td class="px-4 py-2 text-right"><?= number_format($r['Total_Fee'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
    <?php elseif (!$error && isset($_GET['filter'])): ?>
    <section class="card text-center text-[var(--deep)] animate-fadeUp">
      <p>No records found for the selected period.</p>
    </section>
    <?php endif; ?>
  </main>

  <footer class="bg-[var(--navy)] py-4 mt-auto">
    <p class="text-center text-sm text-white">© <?= date('Y') ?> Hotel Reservation System</p>
  </footer>

  <!-- External JS -->
  <script src="../js/financial_info.js"></script>
</body>
</html>
