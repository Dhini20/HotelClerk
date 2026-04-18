<?php
    // About Page - Hotel Reservation System
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About | Crystal Heaven Reservations</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght:300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #DDF4E7;
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #124170, #67C090);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Lift Card */
        .lift-card {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .lift-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(18,65,112,0.25);
        }

        /* Glow Border (Updated with Palette) */
        .glow-border {
            border: 1px solid transparent;
            background-image: linear-gradient(#ffffff, #ffffff),
                              linear-gradient(135deg, #26667F, #67C090);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 1s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Team image hover tilt */
        .team-card:hover img {
            transform: scale(1.06) rotate(2deg);
        }

        .team-card img {
            transition: transform 0.4s ease;
        }
    </style>
</head>

<body class="text-gray-800">

<!-- NAVBAR -->
<nav class="w-full fixed top-0 left-0 z-50" style="background: #ffffff; border-bottom: 2px solid #67C090;">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="font-semibold text-xl gradient-text">Crystal Heaven Reservations</h1>
        <div class="space-x-6">
            <a href="home.php" class="hover:text-[#124170]">Home</a>
            <a href="about.php" class="text-[#124170] font-semibold">About</a>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="pt-32 pb-24 text-center px-6">
    <h1 class="text-5xl md:text-6xl font-bold mb-5 gradient-text reveal">
        Elegant Comfort and Modern Hospitality
    </h1>
    <p class="text-xl text-gray-700 max-w-3xl mx-auto reveal">
        Crystal Heaven blends warm service, modern facilities, and a peaceful
        atmosphere to create a relaxing stay for every guest.
    </p>
</section>

<!-- STORY SECTION -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div class="reveal">
            <h2 class="text-4xl font-semibold mb-4 gradient-text">Our Story</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                The hotel began as a small family-run property built on a passion
                for genuine hospitality. Over the years, the property expanded while
                maintaining the same heartwarming service that welcomed the first 
                guests.
            </p>
            <p class="text-gray-700 leading-relaxed">
                Today, the hotel continues to grow with a focus on comfort,
                quality, and a homely atmosphere that travelers appreciate.
            </p>
        </div>

        <div class="reveal">
            <img src="../images/about1.jpg"
                 class="rounded-2xl shadow-lg">
        </div>
    </div>
</section>

<!-- MISSION & VISION -->
<section class="py-20" style="background: #DDF4E7;">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-4xl font-semibold text-center mb-12 gradient-text reveal">
            Mission & Vision
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="p-8 rounded-2xl glow-border lift-card bg-white reveal">
                <h3 class="text-xl font-semibold mb-3 text-[#124170]">Our Mission</h3>
                <p class="text-gray-700">
                    To deliver a comfortable and reliable stay with 
                    friendly service and thoughtful amenities.
                </p>
            </div>

            <div class="p-8 rounded-2xl glow-border lift-card bg-white reveal">
                <h3 class="text-xl font-semibold mb-3 text-[#124170]">Our Vision</h3>
                <p class="text-gray-700">
                    To be a preferred hotel known for consistency, 
                    warm hospitality, and continuous improvement.
                </p>
            </div>

            <div class="p-8 rounded-2xl glow-border lift-card bg-white reveal">
                <h3 class="text-xl font-semibold mb-3 text-[#124170]">Our Values</h3>
                <p class="text-gray-700">
                    Rooms are designed with soft lighting, modern amenities, 
                    and cozy interiors for restful nights.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- MANAGEMENT TEAM -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-4xl font-semibold text-center mb-12 gradient-text reveal">
            Management Team
        </h2>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-12">

            <div class="team-card text-center p-6 lift-card rounded-2xl bg-[#DDF4E7] reveal">
                <img src="../images/man1.png"
                     class="w-32 h-32 rounded-full mx-auto shadow-md object-cover mb-4">
                <h3 class="text-lg font-semibold text-[#124170]">James Carter</h3>
                <p class="text-gray-700 text-sm mb-3">Chief Executive Officer</p>
            </div>

            <div class="team-card text-center p-6 lift-card rounded-2xl bg-[#DDF4E7] reveal">
                <img src="../images/man2.png"
                     class="w-32 h-32 rounded-full mx-auto shadow-md object-cover mb-4">
                <h3 class="text-lg font-semibold text-[#124170]">Elena Morris</h3>
                <p class="text-gray-700 text-sm mb-3">Head of Operations</p>
            </div>

            <div class="team-card text-center p-6 lift-card rounded-2xl bg-[#DDF4E7] reveal">
                <img src="../images/man3.png"
                     class="w-32 h-32 rounded-full mx-auto shadow-md object-cover mb-4">
                <h3 class="text-lg font-semibold text-[#124170]">Daniel Evans</h3>
                <p class="text-gray-700 text-sm mb-3">Technology Director</p>
            </div>

        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="py-20" style="background: linear-gradient(135deg, #67C090, #26667F);">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-4xl font-semibold text-center mb-12 text-white reveal">
            Why Choose Us
        </h2>

        <div class="grid md:grid-cols-4 gap-10">

            <div class="text-center lift-card p-6 rounded-xl bg-white reveal">
                <div class="text-[#124170] text-4xl mb-3">⚡</div>
                <h3 class="font-semibold text-lg mb-2">Fast & Reliable</h3>
                <p class="text-gray-700 text-sm">Instant bookings with smooth processing.</p>
            </div>

            <div class="text-center lift-card p-6 rounded-xl bg-white reveal">
                <div class="text-[#124170] text-4xl mb-3">🔒</div>
                <h3 class="font-semibold text-lg mb-2">Secure System</h3>
                <p class="text-gray-700 text-sm">Protected with strong security measures.</p>
            </div>

            <div class="text-center lift-card p-6 rounded-xl bg-white reveal">
                <div class="text-[#124170] text-4xl mb-3">🧁</div>
                <h3 class="font-semibold text-lg mb-2">Friendly Service</h3>
                <p class="text-gray-700 text-sm">Comfort stay and responds to every need.</p>
            </div>

            <div class="text-center lift-card p-6 rounded-xl bg-white reveal">
                <div class="text-[#124170] text-4xl mb-3">🛎️</div>
                <h3 class="font-semibold text-lg mb-2">Affordable Luxury</h3>
                <p class="text-gray-700 text-sm">Excellent service at a fair price.</p>
            </div>

        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="py-6 bg-white border-t text-center text-gray-700">
    © <?php echo date('Y'); ?> Hotel Reservation System — All Rights Reserved.
</footer>

<!-- External JS -->
<script src="../js/about_page.js"></script>

</body>
</html>
