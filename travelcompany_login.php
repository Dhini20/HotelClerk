<?php
// Connect to database
include '../db/connection.php';

session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // ✔ TravelCompany login logic
        $stmt = $conn->prepare("SELECT TravelCompanyID, Password FROM TravelCompany WHERE Username = :username LIMIT 1");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $company = $stmt->fetch(PDO::FETCH_ASSOC);

            // ✔ Verify hashed password
            if (password_verify($password, $company['Password'])) {
                $_SESSION['TravelCompanyID'] = $company['TravelCompanyID'];

                // Redirecting to reservtaion page 
                header("Location: travelcompany_booking.php?companyID=" . $company['TravelCompanyID']);
                exit();
            } else {
                $error = "Invalid password.";
            }

        } else {
            $error = "Travel company not found.";
        }

    } catch (PDOException $e) {
        $error = "Login error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Company Login | Hotel Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #DDF4E7 0%, #67C090 40%, #26667F 80%, #124170 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .glass-card {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-transparent fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white tracking-wide">Travel Company Login</h1>
            <div class="space-x-6">
                <a href="home.php" class="text-white hover:text-yellow-300 transition">Home</a>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <main class="flex-grow flex items-center justify-center px-6">
        <div class="glass-card shadow-2xl rounded-2xl p-10 max-w-md w-full" id="login-card">
            <h2 class="text-3xl font-bold text-center text-white mb-6">Company Login</h2>

            <!-- Error -->
            <?php if (!empty($error)) : ?>
                <div class="bg-red-500 text-white text-center rounded-lg py-2 mb-4 animate-bounce">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="travelcompany_login.php" class="space-y-5">

                <!-- Username -->
                <div>
                    <label for="username" class="block text-white font-medium">Username</label>
                    <input type="text" name="username" id="username" required
                           class="w-full px-4 py-3 mt-2 rounded-lg border border-transparent focus:border-yellow-400 
                                  focus:ring-2 focus:ring-yellow-300 outline-none transition bg-white/70">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-white font-medium">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 mt-2 rounded-lg border border-transparent focus:border-yellow-400 
                                  focus:ring-2 focus:ring-yellow-300 outline-none transition bg-white/70">
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-yellow-400 hover:bg-yellow-500 text-black 
                               font-semibold text-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition">
                    Sign In
                </button>

            </form>

            <div class="mt-6 text-center text-white">
                <p>Don't have an account yet? <a href="travel_company_register.php" class="underline hover:text-yellow-300 transition">Register</a></p>
            </div>
        </div>
    </main>

    <footer class="bg-transparent text-center py-4 text-white text-sm">
        © <?= date('Y'); ?> Hotel Reservation System. All rights reserved.
    </footer>

    <script src="../js/TravelCompany_Login.js" defer></script>
</body>
</html>
