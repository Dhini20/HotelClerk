// customer_signin.js

// Smooth page load fade-in
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".animate-fade-in").forEach(el => {
    el.style.opacity = 0;
    el.style.transform = "translateY(30px)";
    setTimeout(() => {
      el.style.transition = "all 1s ease";
      el.style.opacity = 1;
      el.style.transform = "translateY(0)";
    }, 200);
  });
});

// Scroll reveal animations
const revealElements = document.querySelectorAll(".glass-card, .floating-shape");
const revealOnScroll = () => {
  const triggerBottom = window.innerHeight * 0.85;
  revealElements.forEach(el => {
    const boxTop = el.getBoundingClientRect().top;
    if (boxTop < triggerBottom) {
      el.classList.add("reveal");
      el.style.transition = "all 1s ease";
      el.style.opacity = 1;
      el.style.transform = "translateY(0)";
    }
  });
};
window.addEventListener("scroll", revealOnScroll);

// Hover floating glow effect
document.querySelectorAll("button, input, a").forEach(el => {
  el.addEventListener("mouseenter", () => {
    el.style.transition = "all 0.3s ease";
    el.style.boxShadow = "0 0 15px rgba(103,192,144,0.6)";
    el.style.transform = "scale(1.03)";
  });
  el.addEventListener("mouseleave", () => {
    el.style.boxShadow = "none";
    el.style.transform = "scale(1)";
  });
});

// Floating background shape movement with mouse
document.addEventListener("mousemove", e => {
  document.querySelectorAll(".floating-shape").forEach((shape, i) => {
    const speed = (i + 1) * 0.02;
    const x = (window.innerWidth / 2 - e.pageX) * speed;
    const y = (window.innerHeight / 2 - e.pageY) * speed;
    shape.style.transform = `translate(${x}px, ${y}px)`;
  });
});
