<?php
// Connect to database
include '../db/connection.php';

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = $_POST['company_name'] ?? '';
    $address     = $_POST['address'] ?? '';
    $email       = $_POST['email'] ?? '';
    $phone       = $_POST['phone'] ?? '';
    $username    = $_POST['username'] ?? '';
    $password    = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO TravelCompany (CompanyName, Address, Email, PhoneNo, Username, Password) 
                                VALUES (:company_name, :address, :email, :phone, :username, :password)");
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Something went wrong. Try again.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Company Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#124170] via-[#26667F] to-[#67C090] min-h-screen flex flex-col">

    <!-- Header -->
    <header class="p-6 text-center text-white text-3xl font-bold tracking-wider">
        🚐 Travel Company Registration
    </header>

    <!-- Main -->
    <main class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">

        <!-- Floating Orbs -->
        <div class="absolute w-72 h-72 bg-[#DDF4E7] opacity-20 rounded-full blur-3xl top-10 left-10 animate-pulse"></div>
        <div class="absolute w-96 h-96 bg-[#67C090] opacity-20 rounded-full blur-3xl bottom-10 right-10 animate-pulse"></div>

        <!-- Form Card -->
        <div class="w-full max-w-lg bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl p-8 text-white animate-slideIn">

            <h2 class="text-2xl font-semibold mb-6 text-center">Register Travel Company</h2>

            <?php if (!empty($message)): ?>
                <div class="mb-4 text-center text-lg font-medium bg-green-600/70 rounded-lg p-2">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">

                <!-- Company Name -->
                <input type="text" name="company_name" placeholder="Company Name" required
                    class="w-full p-3 rounded-lg bg-white/10 focus:bg-white/20 border border-white/30 
                           focus:border-[#67C090] outline-none transition">

                <!-- Address -->
                <input type="text" name="address" placeholder="Company Address" required
                    class="w-full p-3 rounded-lg bg-white/10 focus:bg-white/20 border border-white/30 
                           focus:border-[#67C090] outline-none transition">

                <!-- Email -->
                <input type="email" name="email" placeholder="Email Address" required
                    class="w-full p-3 rounded-lg bg-white/10 focus:bg-white/20 border border-white/30 
                           focus:border-[#67C090] outline-none transition">

                <!-- Phone -->
                <input type="text" name="phone" placeholder="Phone Number" required
                    class="w-full p-3 rounded-lg bg-white/10 focus:bg-white/20 border border-white/30 
                           focus:border-[#67C090] outline-none transition">

                <!-- Username -->
                <input type="text" name="username" placeholder="Username" required
                    class="w-full p-3 rounded-lg bg-white/10 focus:bg-white/20 border border-white/30 
                           focus:border-[#67C090] outline-none transition">

                <!-- Password -->
                <input type="password" name="password" placeholder="Password" required
                    class="w-full p-3 rounded-lg bg-white/10 focus:bg-white/20 border border-white/30 
                           focus:border-[#67C090] outline-none transition">

                <!-- Register Button -->
                <button type="submit"
                    class="w-full py-3 bg-[#67C090] hover:bg-[#26667F] rounded-lg text-lg font-semibold 
                           transition transform hover:scale-105 hover:shadow-xl">
                    Register
                </button>
            </form>

            <p class="text-center mt-4">
                Already registered?
                <a href="travelcompany_login.php" class="text-[#DDF4E7] hover:text-white transition underline">Log In</a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="p-4 text-center text-white bg-[#124170]">
        © <?= date("Y"); ?> Hotel Management System | All Rights Reserved
    </footer>

    <script src="../js/travel_company_register.js"></script>
</body>
</html>
