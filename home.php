<?php
// Minimal PHP placeholders for future backend integration
$siteTitle = "Crystal Heaven Reservations";
$heroHeadline = "Comfort. Class. Care.";
$heroSub = "Seamless reservations, effortless stays — tailored for every guest.";
$welcomeMsg = "Welcome to Crystal Heaven Reservations!";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($siteTitle); ?></title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Optional: Tailwind config for extended utilities (inline) -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            aurora: {
              50: '#DDF4E7',
              100: '#67C090',
              300: '#A8BBA3',
              500: '#26667F',
              700: '#124170'
            }
          },
          keyframes: {
            floaty: {
              '0%': { transform: 'translateY(0px)' },
              '50%': { transform: 'translateY(-6px)' },
              '100%': { transform: 'translateY(0px)' }
            },
            glow: {
              '0%': { boxShadow: '0 0 0 rgba(58,160,255,0)' },
              '50%': { boxShadow: '0 8px 40px rgba(58,160,255,0.18)' },
              '100%': { boxShadow: '0 0 0 rgba(58,160,255,0)' }
            }
          },
          animation: {
            floaty: 'floaty 6s ease-in-out infinite',
            glow: 'glow 2.6s ease-in-out infinite'
          }
        }
      }
    }
  </script>

  <style>
    /* Small utilities not in Tailwind for this page */
    .typewriter > .wrap {
      border-right: 2px solid rgba(255,255,255,0.7);
      padding-right: 2px;
    }
    .reveal { opacity: 0; transform: translateY(18px) scale(0.995); transition: all 700ms cubic-bezier(.2,.9,.2,1); }
    .reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }
    .tilt { transform-style: preserve-3d; transition: transform 200ms ease; will-change: transform; }
    /* subtle focus ring fallback */
    .focus-ring:focus { outline: none; box-shadow: 0 0 0 4px rgba(58,160,255,0.12); border-radius: 0.5rem; }

        /* Floating glowing orbs effect */
    .orb {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle at center, rgba(58,160,255,0.3), transparent 70%);
    animation: float 20s linear infinite;
    }

    @keyframes float {
    from { transform: translateY(100vh) scale(0.5); opacity: 0; }
    20% { opacity: 1; }
    to { transform: translateY(-20vh) scale(1.2); opacity: 0; }
    }

  </style>
</head>
<body class="bg-gradient-to-b from-aurora-50 to-white text-slate-800 antialiased">
<!-- Animated background canvas -->
<canvas id="bgCanvas" class="fixed inset-0 -z-10"></canvas>


  <!-- Top sticky nav -->
  <header class="sticky top-0 z-50 backdrop-blur-md">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="#" class="flex items-center gap-3 select-none">
          <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-aurora-500 to-aurora-700 flex items-center justify-center text-white shadow-lg transform hover:scale-105 transition">
            <!-- Simple logo mark -->
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden>
              <path d="M3 12c0-5 4-9 9-9s9 4 9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="3" fill="white"/>
            </svg>
          </div>
          <span class="text-lg font-semibold tracking-tight"><?php echo htmlspecialchars($siteTitle); ?></span>
        </a>
      </div>

      <ul class="hidden md:flex items-center gap-6 text-sm font-medium">
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="#features">Features</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="about.php">About</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="cutomer_signup.php">Sign Up</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="customer_signin.php">Sign In</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="travelcompany_login.php">Block Bookings</a></li>
        <li><a class="nav-item px-2 py-1 rounded-md hover:text-aurora-700 focus-ring" href="#contact">Contact</a></li>
      </ul>

      <div class="flex items-center gap-3">
        <a href="#reserve" class="hidden md:inline-block px-4 py-2 rounded-full bg-aurora-500 text-white font-semibold shadow hover:scale-105 transition transform">Reserve Now</a>
        <button id="mobileMenuBtn" class="md:hidden p-2 rounded-md focus-ring" aria-label="Open menu">
          <svg width="22" height="22" viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        </button>
      </div>
    </nav>
  </header>

  <!-- Mobile drawer (simple) -->
  <div id="mobileDrawer" class="fixed inset-0 bg-black/40 hidden z-40">
    <div class="absolute right-0 top-0 w-72 h-full bg-white p-6 shadow-xl">
      <button id="closeDrawer" class="mb-6 p-2 rounded-md focus-ring">Close</button>
      <nav class="flex flex-col gap-4">
        <a class="text-lg font-medium" href="#features">Features</a>
        <a class="text-lg font-medium" href="#rooms">Rooms</a>
        <a class="text-lg font-medium" href="#testimonials">Testimonials</a>
        <a class="text-lg font-medium" href="#contact">Contact</a>
        <a id="reserveMobile" class="mt-4 inline-block px-4 py-2 rounded-full bg-aurora-500 text-white font-semibold" href="#reserve">Reserve</a>
      </nav>
    </div>
  </div>

  <!-- Hero -->
  <main>
    <section class="relative overflow-hidden">

    <!-- Video background -->
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover -z-10">
      <source src="../images/Home.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>

    <div class="absolute inset-0 -z-5" style="background: linear-gradient(to right, rgba(0, 0, 0, 0.57), rgba(0, 0, 0, 0.57));"></div>




      <div class="max-w-7xl mx-auto px-6 py-20 md:py-28 relative z-10">
        <div class="grid md:grid-cols-2 gap-10 items-center">
          <div class="space-y-6">
            <p class="text-sm text-aurora-700 font-semibold inline-block px-3 py-1 rounded-full bg-aurora-50 shadow-sm reveal" data-delay="100">Luxury stays — simplified</p>

            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight reveal" data-delay="200">
              <span class="typewriter text-white"><span class="wrap"><?php echo htmlspecialchars($heroHeadline); ?></span></span>
            </h1>


            <p class="text-lg text-aurora-50 max-w-xl reveal" data-delay="300"><?php echo htmlspecialchars($heroSub); ?></p>

            <div class="flex gap-4 items-center mt-4 reveal" data-delay="400">
              <a href="#reserve" class="cta-btn inline-flex items-center gap-3 px-5 py-3 rounded-full bg-aurora-500 text-white font-semibold shadow-lg transform transition hover:scale-105 focus-ring">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 7h18M5 11h14M8 15h8" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Book a Room
              </a>

                <a href="#features" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 text-aurora-50 hover:border-aurora-300 focus-ring">Explore Features</a>
            </div>

            <div class="mt-6 flex items-center gap-4 reveal" data-delay="450">
              <div class="text-sm">
                <div class="font-semibold text-white"><?php echo htmlspecialchars($welcomeMsg); ?></div>
                <div class="text-aurora-50">Secure payments • Flexible cancellations • 24/7 support</div>
              </div>
            </div>
          </div>

          <!-- visual card -->
          <div class="relative">
            <div class="tilt bg-white rounded-2xl shadow-2xl p-6 md:p-8 reveal" data-delay="350" data-tilt>
              <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl overflow-hidden">
                  <img src="../Images/home1.jpg" alt="room" class="w-full h-40 object-cover rounded-lg"/>
                </div>
                <div class="rounded-xl overflow-hidden">
                  <img src="../Images/home2.jpg" alt="lobby" class="w-full h-40 object-cover rounded-lg"/>
                </div>
              </div>

              <div class="mt-4">
                <h3 class="font-semibold text-lg">Featured Suite</h3>
                <p class="text-sm text-slate-500 mt-1">Elegant interiors, panoramic view, smart amenities.</p>

                <div class="mt-4 flex items-center justify-between">
                  <div class="text-sm">
                    <div class="font-bold">$129 <span class="text-slate-400 font-medium">/ night</span></div>
                    <div class="text-slate-400 text-xs">Free cancellation • Breakfast included</div>
                  </div>
                  <a href="#reserve" class="px-3 py-1.5 rounded-md bg-aurora-500 text-white text-sm font-medium shadow hover:scale-105 transform">Reserve</a>
                </div>
              </div>
            </div>

            <!-- small badge -->
            <div class="absolute -left-6 -top-6 bg-white rounded-2xl p-3 shadow-lg animate-glow hidden md:block">
              <div class="text-xs text-slate-600">Top Rated</div>
              <div class="font-semibold">4.8 ★</div>
            </div>
          </div>
        </div>
      </div>


    </section>

    <!-- Features -->
    <section id="features" class="max-w-7xl mx-auto px-6 py-16">
      <div class="text-center mb-12 reveal" data-delay="100">
        <h2 class="text-3xl font-bold">Core Capabilities</h2>
        <p class="text-slate-500 mt-2">Reservation, check-in/out, billing, group bookings, long-stay suites — all in a unified system.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <article class="reveal p-6 bg-white rounded-2xl shadow transform hover:-translate-y-2 transition tilt" data-delay="150">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-aurora-100 flex items-center justify-center text-aurora-700">
              <!-- icon -->
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 7h18v10H3z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Online Reservations</h3>
              <p class="text-sm text-slate-500">Customer-managed bookings, secure payment options, and flexible cancellations.</p>
            </div>
          </div>
        </article>

        <article class="reveal p-6 bg-white rounded-2xl shadow transform hover:-translate-y-2 transition tilt" data-delay="220">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-aurora-100 flex items-center justify-center text-aurora-700">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Front Desk Operations</h3>
              <p class="text-sm text-slate-500">Check-in/out, room assignment, no-show handling, and on-the-fly reservations.</p>
            </div>
          </div>
        </article>

        <article class="reveal p-6 bg-white rounded-2xl shadow transform hover:-translate-y-2 transition tilt" data-delay="300">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-aurora-100 flex items-center justify-center text-aurora-700">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 7h16v10H4z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Flexible Billing</h3>
              <p class="text-sm text-slate-500">Automated billing for stays, extras, no-shows, and corporate invoices.</p>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Rooms / Suites -->
    <section id="rooms" class="bg-aurora-50 py-14">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-8 reveal" data-delay="120">
          <h3 class="text-2xl font-bold">Rooms & Residential Suites</h3>
          <a href="#reserve" class="text-aurora-700 font-medium">View all rooms →</a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <div class="reveal bg-white rounded-2xl p-5 shadow tilt">
            <img class="w-full h-40 object-cover rounded-lg mb-4" src="../images/suit1.jpg" alt="standard room">
            <h4 class="font-semibold">Standard Room</h4>
            <p class="text-sm text-slate-500 mt-1">Comfortable, functional and ideal for short stays.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-sm font-bold">$79 <span class="text-slate-400 font-medium">/ night</span></div>
              <a class="px-3 py-1 rounded-md bg-white border border-slate-200 hover:bg-aurora-100" href="customer_signin.php">Select</a>
            </div>
          </div>

          <div class="reveal bg-white rounded-2xl p-5 shadow tilt">
            <img class="w-full h-40 object-cover rounded-lg mb-4" src="../images/suit2.jpg" alt="suite">
            <h4 class="font-semibold">Executive Suite</h4>
            <p class="text-sm text-slate-500 mt-1">Spacious living area, dedicated workspace, and premium amenities.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-sm font-bold">$199 <span class="text-slate-400 font-medium">/ night</span></div>
              <a class="px-3 py-1 rounded-md bg-white border border-slate-200 hover:bg-aurora-100" href="customer_signin.php">Select</a>
            </div>
          </div>

          <div class="reveal bg-white rounded-2xl p-5 shadow tilt">
            <img class="w-full h-40 object-cover rounded-lg mb-4" src="../images/suit3.jpg" alt="residential suite">
            <h4 class="font-semibold">Residential Suite</h4>
            <p class="text-sm text-slate-500 mt-1">Weekly or monthly stays with dedicated rates for long-term guests.</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="text-sm font-bold">$950 <span class="text-slate-400 font-medium">/ week</span></div>
              <a class="px-3 py-1 rounded-md bg-white border border-slate-200 hover:bg-aurora-100" href="customer_signin.php">Select</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="max-w-7xl mx-auto px-6 py-16">
      <div class="text-center mb-8 reveal" data-delay="120">
        <h2 class="text-2xl font-bold">Guest Testimonials</h2>
        <p class="text-slate-500 mt-2">Real reviews from satisfied guests and travel partners.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <blockquote class="reveal p-6 bg-white rounded-2xl shadow tilt">
          <p class="text-slate-600">"Seamless reservation process and friendly staff. The suite was spotless and the breakfast was unforgettable."</p>
          <footer class="mt-4 text-sm text-slate-500">— Amanda R., Business Traveler</footer>
        </blockquote>

        <blockquote class="reveal p-6 bg-white rounded-2xl shadow tilt">
          <p class="text-slate-600">"Group booking for our travel agency was handled professionally with clear billing and discounting for multiple rooms."</p>
          <footer class="mt-4 text-sm text-slate-500">— Pacific Travels</footer>
        </blockquote>

        <blockquote class="reveal p-6 bg-white rounded-2xl shadow tilt">
          <p class="text-slate-600">"Long-stay residential suite provided excellent value and housekeeping was consistently excellent."</p>
          <footer class="mt-4 text-sm text-slate-500">— R. Fernando, Extended Stay Guest</footer>
        </blockquote>
      </div>
    </section>

    <!-- CTA / Reserve -->
    <section id="reserve" class="bg-aurora-700 text-white py-14">
      <div class="max-w-5xl mx-auto px-6 text-center">
        <h3 class="text-2xl font-bold reveal" data-delay="120">Ready to reserve a room or suite?</h3>
        <p class="text-slate-100 mt-2 reveal" data-delay="180">Flexible booking options: card on file, pay later, corporate billing for travel companies.</p>
        <div class="mt-6 flex justify-center gap-4 reveal" data-delay="240">
          <a href="customer_signin.php" class="px-6 py-3 rounded-full bg-white text-aurora-700 font-semibold shadow hover:scale-105 transform">Sign In</a>
          <a href="cutomer_signup.php" class="px-6 py-3 rounded-full border border-white/30 text-white">Sign Up</a>
        </div>
      </div>
    </section>

    <!-- Contact / Footer -->
    <footer id="contact" class="bg-white border-t mt-8">
      <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-8">
        <div>
          <h4 class="font-semibold">Crystal Heaven</h4>
          <p class="text-slate-500 mt-2">123 Oceanview Drive, Colombo • +94 11 123 4567 • reservations@aurorahotel.example</p>
          <div class="mt-4 flex gap-3">
            <a class="p-2 rounded-md bg-aurora-50">FB</a>
            <a class="p-2 rounded-md bg-aurora-50">IG</a>
            <a class="p-2 rounded-md bg-aurora-50">TW</a>
          </div>
        </div>

        <div>
          <h5 class="font-medium">Useful Links</h5>
          <ul class="mt-3 text-slate-600 space-y-2">
            <li><a href="#features">Features</a></li>
            <li><a href="#rooms">Rooms & Suites</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
          </ul>
        </div>

        <div>
          <h5 class="font-medium">Subscribe</h5>
          <p class="text-slate-500 text-sm mt-2">Receive offers, news and promotions.</p>
          <form class="mt-4 flex gap-2">
            <input aria-label="email" type="email" placeholder="Email address" class="w-full px-3 py-2 rounded-md border border-slate-200 focus-ring" />
            <button type="submit" class="px-4 py-2 rounded-md bg-aurora-500 text-white">Join</button>
          </form>
        </div>
      </div>

      <div class="border-t py-4 text-center text-sm text-slate-500">
        &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteTitle); ?>. All rights reserved.
      </div>
    </footer>
  </main>

  <!-- Link to external JS -->
  <script src="../js/home_page.js" defer></script>
</body>
</html>
