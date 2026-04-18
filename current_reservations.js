// Page animations for reservations list
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".reservation-card");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add("animate-rise");
    });
  }, { threshold: 0.2 });

  cards.forEach(card => observer.observe(card));
});

// Floating tilt effect
document.addEventListener("mousemove", (e) => {
  document.querySelectorAll(".reservation-card").forEach(el => {
    const x = (window.innerWidth / 2 - e.pageX) / 60;
    const y = (window.innerHeight / 2 - e.pageY) / 60;
    el.style.transform = `rotateY(${x}deg) rotateX(${y}deg) scale(1.02)`;
  });
});

document.addEventListener("mouseleave", () => {
  document.querySelectorAll(".reservation-card").forEach(el => {
    el.style.transform = "rotateY(0deg) rotateX(0deg)";
  });
});

// Animations CSS
const style = document.createElement("style");
style.innerHTML = `
  .animate-rise { animation: rise 1s ease forwards; }
  @keyframes rise {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }
`;
document.head.appendChild(style);
