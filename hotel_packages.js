/* hotel_packages.js
   Interactive animations & effects for customer_reservation.php
*/

// Page Load Animations
document.addEventListener("DOMContentLoaded", () => {
  const heroTitle = document.querySelector("h1");
  const heroText = document.querySelector("section p");
  heroTitle.classList.add("fade-in");
  heroText.classList.add("slide-up");

  // Reveal package cards on scroll
  const cards = document.querySelectorAll(".package-card");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate-rise");
      }
    });
  }, { threshold: 0.2 });

  cards.forEach(card => observer.observe(card));
});

// Floating decorative effect (mouse movement)
document.addEventListener("mousemove", (e) => {
  document.querySelectorAll(".package-card").forEach(card => {
    const x = (window.innerWidth / 2 - e.pageX) / 60;
    const y = (window.innerHeight / 2 - e.pageY) / 60;
    card.style.transform = `rotateY(${x}deg) rotateX(${y}deg)`;
  });
});

// Reset tilt effect when mouse leaves window
document.addEventListener("mouseleave", () => {
  document.querySelectorAll(".package-card").forEach(card => {
    card.style.transform = "rotateY(0deg) rotateX(0deg)";
  });
});

/* Custom CSS animations via JS (Tailwind extended classes are not added, so we define keyframes here dynamically if needed) */
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
