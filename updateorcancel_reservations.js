// Animate form fields on load
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  form.classList.add("animate-fadeIn");
});

// Add hover tilt effect
document.addEventListener("mousemove", (e) => {
  const card = document.querySelector("form");
  if (!card) return;
  const x = (window.innerWidth / 2 - e.pageX) / 80;
  const y = (window.innerHeight / 2 - e.pageY) / 80;
  card.style.transform = `rotateY(${x}deg) rotateX(${y}deg) scale(1.01)`;
});

document.addEventListener("mouseleave", () => {
  const card = document.querySelector("form");
  if (card) card.style.transform = "rotateY(0deg) rotateX(0deg)";
});

// Animations CSS
const style = document.createElement("style");
style.innerHTML = `
  .animate-fadeIn { animation: fadeIn 1s ease forwards; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }
`;
document.head.appendChild(style);
