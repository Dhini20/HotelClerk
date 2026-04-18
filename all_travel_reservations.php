<?php
// Connect to database
include '../db/connection.php';

// Get admin ID (kept same as existing system)
$adminID = isset($_GET['adminID']) ? $_GET['adminID'] : '';

// Get selected date from query string (if any)
$selectedDate = isset($_GET['checkin_date']) ? $_GET['checkin_date'] : '';

// Build query
if (!empty($selectedDate)) {
    $query = $conn->prepare("
        SELECT rtc.*, tc.CompanyName, tc.PhoneNo, tc.Email, tc.Address
        FROM ReservationTravelCompany rtc
        JOIN TravelCompany tc ON rtc.TravelCompanyID = tc.TravelCompanyID
        WHERE rtc.CheckIn = :checkin_date
        ORDER BY rtc.CheckIn DESC
    ");
    $query->bindParam(':checkin_date', $selectedDate);
} else {
    $query = $conn->prepare("
        SELECT rtc.*, tc.CompanyName, tc.PhoneNo, tc.Email, tc.Address
        FROM ReservationTravelCompany rtc
        JOIN TravelCompany tc ON rtc.TravelCompanyID = tc.TravelCompanyID
        ORDER BY rtc.CheckIn DESC
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
    <title>Travel Company Reservations</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom JavaScript -->
    <script defer src="../js/all_travel_reservations.js"></script>

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
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-8 title-glow animate-fadeIn">
            Travel Company Reservations
        </h2>

        <!-- Filter Section -->
        <div class="filter-section text-center animate-fadeIn">
            <form method="GET" action="">
                <input type="hidden" name="adminID" value="<?= $adminID ?>">
                <label for="checkin_date" class="font-semibold text-lg text-[#124170]">
                    Filter by Check-In Date:
                </label>
                <input 
                    type="date" 
                    id="checkin_date" 
                    name="checkin_date" 
                    value="<?= htmlspecialchars($selectedDate) ?>" 
                    class="border border-[#26667F] rounded-lg p-2 mx-2"
                    required
                >
                <button type="submit" class="btn-glow px-5 py-2 rounded-lg font-semibold">
                    Filter
                </button>
                <a href="all_travel_reservations.php?adminID=<?= $adminID ?>" 
                   class="ml-3 text-[#124170] font-semibold underline hover:text-[#26667F]">
                    Clear Filter
                </a>
            </form>
        </div>

        <?php if (count($reservations) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <?php foreach ($reservations as $res): ?>
                    <div class="glass-card p-6 shadow-lg animate-fadeUp">

                        <h3 class="text-2xl font-semibold mb-3 text-[#124170]">
                            Reservation #<?= htmlspecialchars($res['ReservationID']) ?>
                        </h3>

                        <p><strong>Check-In:</strong> <?= htmlspecialchars($res['CheckIn']) ?></p>
                        <p><strong>Check-Out:</strong> <?= htmlspecialchars($res['CheckOut']) ?></p>
                        <p><strong>Guests:</strong> <?= htmlspecialchars($res['NumGuests']) ?></p>
                        <p><strong>Rooms:</strong> <?= htmlspecialchars($res['NumRooms']) ?></p>
                        <p><strong>Beds:</strong> <?= htmlspecialchars($res['BedCount']) ?></p>
                        <p><strong>Created:</strong> <?= htmlspecialchars($res['CreatedDate']) ?></p>
                        <p><strong>Total Fee:</strong> LKR <?= htmlspecialchars($res['Total_Fee']) ?></p>

                        <hr class="my-3 border-[#26667F]">

                        <h4 class="text-lg font-semibold mb-2 text-[#124170]">
                            Travel Company Details
                        </h4>

                        <p><strong>Company Name:</strong> <?= htmlspecialchars($res['CompanyName']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($res['PhoneNo']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($res['Email']) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($res['Address']) ?></p>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <p class="text-center text-lg text-gray-700 mt-10">
                No travel company reservations found for this date.
            </p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white/30 backdrop-blur-md py-4 mt-auto">
        <div class="text-center text-[#124170] font-medium">
            © <?= date('Y') ?> Hotel Reservation System | Designed with ❤️
        </div>
    </footer>
</body>
</html>
