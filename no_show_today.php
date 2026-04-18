<?php
// Connect to database
include '../db/connection.php';

// Fetch today's date
$today = date('Y-m-d');

// Fetch reservations where CheckIn is today and show1 = 0
$query = $conn->prepare("
    SELECT r.*, c.FirstName, c.LastName, c.PhoneNo, c.Email, c.Address, c.NIC
    FROM Reservation r
    JOIN Customer c ON r.CustomerID = c.CustomerID
    WHERE r.CheckIn = :today AND r.show1 = 0
");
$query->bindParam(':today', $today);
$query->execute();
$reservations = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Show Customers</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom JavaScript for animations -->
    <script defer src="../js/no_show.js"></script>

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

        .title-glow {
            text-shadow: 0 0 10px rgba(18, 65, 112, 0.5);
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
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-10">
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-10 title-glow animate-fadeIn">No Show Customers</h2>

        <?php if (count($reservations) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($reservations as $res): ?>
                    <div class="glass-card p-6 shadow-lg animate-fadeUp">
                        <h3 class="text-2xl font-semibold mb-3 text-[#124170]">Reservation #<?= htmlspecialchars($res['ReservationID']) ?></h3>
                        <p><strong>Check-In:</strong> <?= htmlspecialchars($res['CheckIn']) ?></p>
                        <p><strong>Check-Out:</strong> <?= htmlspecialchars($res['CheckOut']) ?></p>
                        <p><strong>Guests:</strong> <?= htmlspecialchars($res['NumGuests']) ?></p>
                        <p><strong>Beds:</strong> <?= htmlspecialchars($res['BedCount']) ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($res['Status']) ?></p>
                        <p><strong>Total Fee:</strong> LKR <?= htmlspecialchars($res['Total_Fee']) ?></p>
                        <hr class="my-3 border-[#26667F]">

                        <h4 class="text-lg font-semibold mb-2 text-[#124170]">Customer Details</h4>
                        <p><strong>Name:</strong> <?= htmlspecialchars($res['FirstName'] . " " . $res['LastName']) ?></p>
                        <p><strong>NIC:</strong> <?= htmlspecialchars($res['NIC']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($res['PhoneNo']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($res['Email']) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($res['Address']) ?></p>

                        <button onclick="window.location.href='generate_pdf.php?id=<?= $res['ReservationID'] ?>'"
                                class="mt-4 w-full py-2 rounded-lg btn-glow font-semibold">
                            Download PDF
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-lg text-gray-700 mt-10">No "No Show" customers for today.</p>
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
