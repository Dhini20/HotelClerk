/* hotel_packages1.js
   Animations, hover effects, and fee calculation logic for room_reservation.php
*/

// Page Load Animations
document.addEventListener("DOMContentLoaded", () => {
  const heroTitle = document.querySelector("h1");
  const heroText = document.querySelector("section p");
  heroTitle.classList.add("fade-in");
  heroText.classList.add("slide-up");

  // Reveal form and sections on scroll
  const sections = document.querySelectorAll("section, form, .package-card");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add("animate-rise");
      }
    });
  }, { threshold: 0.2 });

  sections.forEach(sec => observer.observe(sec));
});

// Floating tilt effect for interactive cards and form
document.addEventListener("mousemove", (e) => {
  document.querySelectorAll("form, .package-card").forEach(el => {
    const x = (window.innerWidth / 2 - e.pageX) / 60;
    const y = (window.innerHeight / 2 - e.pageY) / 60;
    el.style.transform = `rotateY(${x}deg) rotateX(${y}deg)`;
  });
});

document.addEventListener("mouseleave", () => {
  document.querySelectorAll("form, .package-card").forEach(el => {
    el.style.transform = "rotateY(0deg) rotateX(0deg)";
  });
});

// Calculate Fee Logic
document.getElementById("calculateFee").addEventListener("click", () => {
  const checkin = document.getElementById("checkin").value;
  const checkout = document.getElementById("checkout").value;
  const numGuests = parseInt(document.getElementById("numguests").value);
  const bedCount = parseInt(document.getElementById("bedcount").value);
  const optionalCharges = document.querySelectorAll('input[name="optional_charges[]"]:checked');

  if(!checkin || !checkout){
    alert("Please select check-in and check-out dates.");
    return;
  }

  const date1 = new Date(checkin);
  const date2 = new Date(checkout);
  let timeDiff = date2 - date1;
  let days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
  if(days <= 0) days = 1;

  let total = 0;
  total += days * 33;      // per day
  total += numGuests * 16; // per guest
  total += bedCount * 14;  // per bed

  // ✅ use data-amount directly
  optionalCharges.forEach(chk => {
    const amount = parseFloat(chk.getAttribute("data-amount"));
    if(!isNaN(amount)) total += amount;
  });

  document.getElementById("totalFee").textContent = total.toFixed(2);
  document.getElementById('hiddenTotalFee').value = total;
});

/* Custom animations */
const style = document.createElement("style");
style.innerHTML = `
  .fade-in { animation: fadeIn 1.2s ease forwards; }
  .slide-up { animation: slideUp 1.2s ease forwards; }
  .animate-rise { animation: rise 1s ease forwards; }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes rise {
    from { opacity: 0; transform: translateY(50px); }
    to { opacity: 1; transform: translateY(0); }
  }
`;
document.head.appendChild(style);
