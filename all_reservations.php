<?php
// Connect to database
include '../db/connection.php';

// Get selected date from query string (if any)
$selectedDate = isset($_GET['checkin_date']) ? $_GET['checkin_date'] : '';

// Build query
if (!empty($selectedDate)) {
    $query = $conn->prepare("
        SELECT r.*, c.FirstName, c.LastName, c.PhoneNo, c.Email, c.Address, c.NIC
        FROM Reservation r
        JOIN Customer c ON r.CustomerID = c.CustomerID
        WHERE r.CheckIn = :checkin_date
        ORDER BY r.CheckIn DESC
    ");
    $query->bindParam(':checkin_date', $selectedDate);
} else {
    $query = $conn->prepare("
        SELECT r.*, c.FirstName, c.LastName, c.PhoneNo, c.Email, c.Address, c.NIC
        FROM Reservation r
        JOIN Customer c ON r.CustomerID = c.CustomerID
        ORDER BY r.CheckIn DESC
    ");
}

$query->execute();
$reservations = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reservations</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom JavaScript -->
    <script defer src="../js/all_reservations.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #DDF4E7, #67C090, #26667F, #124170);
            background-size: 200% 200%;
            min-height: 100vh;
            color: #124170;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease-in-out;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(31, 38, 135, 0.4);
        }

        .btn-glow {
            background-color: #26667F;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            background-color: #124170;
            box-shadow: 0 0 10px #67C090, 0 0 20px #26667F;
        }

        .btn-show {
            background-color: #67C090;
            color: #124170;
            transition: all 0.3s ease;
        }

        .btn-show:hover {
            background-color: #45a57a;
            box-shadow: 0 0 10px #124170, 0 0 20px #67C090;
        }

        .title-glow {
            text-shadow: 0 0 10px rgba(18, 65, 112, 0.5);
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(18, 65, 112, 0.2);
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white/30 backdrop-blur-lg py-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold text-[#124170]">Hotel Reservation System</h1>
            <div class="space-x-4">
                <a href="home.php" class="text-[#124170] hover:text-[#26667F] font-medium">Home</a>
                <a href="admin_dashboard.php" class="text-[#124170] hover:text-[#26667F] font-medium">Dashboard</a>
                <a href="admin_login.php" class="text-[#124170] hover:text-red-600 font-medium">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-10">
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-8 title-glow animate-fadeIn">All Reservations</h2>

        <!-- Filter Section -->
        <div class="filter-section text-center animate-fadeIn">
            <form method="GET" action="">
                <label for="checkin_date" class="font-semibold text-lg text-[#124170]">Filter by Check-In Date:</label>
                <input 
                    type="date" 
                    id="checkin_date" 
                    name="checkin_date" 
                    value="<?= htmlspecialchars($selectedDate) ?>" 
                    class="border border-[#26667F] rounded-lg p-2 mx-2"
                    required
                >
                <button 
                    type="submit" 
                    class="btn-glow px-5 py-2 rounded-lg font-semibold"
                >
                    Filter
                </button>
                <a href="all_reservations.php" class="ml-3 text-[#124170] font-semibold underline hover:text-[#26667F]">
                    Clear Filter
                </a>
            </form>
        </div>

        <?php if (count($reservations) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($reservations as $res): ?>
                    <div class="glass-card p-6 shadow-lg animate-fadeUp" data-id="<?= $res['ReservationID'] ?>">
                        <h3 class="text-2xl font-semibold mb-3 text-[#124170]">Reservation #<?= htmlspecialchars($res['ReservationID']) ?></h3>
                        <p><strong>Check-In:</strong> <?= htmlspecialchars($res['CheckIn']) ?></p>
                        <p><strong>Check-Out:</strong> <?= htmlspecialchars($res['CheckOut']) ?></p>
                        <p><strong>Guests:</strong> <?= htmlspecialchars($res['NumGuests']) ?></p>
                        <p><strong>Beds:</strong> <?= htmlspecialchars($res['BedCount']) ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($res['Status']) ?></p>
                        <p><strong>Total Fee:</strong> LKR <?= htmlspecialchars($res['Total_Fee']) ?></p>
                        <p><strong>Show Status:</strong>
                            <?php if ($res['show1'] == 1): ?>
                                <span class="text-green-700 font-semibold">Shown</span>
                            <?php else: ?>
                                <span class="text-red-700 font-semibold">Not Shown</span>
                            <?php endif; ?>
                        </p>
                        <hr class="my-3 border-[#26667F]">

                        <h4 class="text-lg font-semibold mb-2 text-[#124170]">Customer Details</h4>
                        <p><strong>Name:</strong> <?= htmlspecialchars($res['FirstName'] . " " . $res['LastName']) ?></p>
                        <p><strong>NIC:</strong> <?= htmlspecialchars($res['NIC']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($res['PhoneNo']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($res['Email']) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($res['Address']) ?></p>

                        <div class="flex gap-3 mt-4">
                            <button onclick="window.location.href='generate_pdf.php?id=<?= $res['ReservationID'] ?>'"
                                    class="flex-1 py-2 rounded-lg btn-glow font-semibold">
                                Download PDF
                            </button>
                            <?php if ($res['show1'] == 0): ?>
                                <button class="flex-1 py-2 rounded-lg btn-show font-semibold mark-show-btn"
                                        data-id="<?= $res['ReservationID'] ?>">
                                    Mark as Show
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-lg text-gray-700 mt-10">No reservations found for this date.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white/30 backdrop-blur-md py-4 mt-auto">
        <div class="text-center text-[#124170] font-medium">
            © <?= date('Y') ?> Hotel Reservation System
        </div>
    </footer>
</body>
</html>
