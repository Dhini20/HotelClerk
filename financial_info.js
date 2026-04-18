document.addEventListener("DOMContentLoaded", () => {
  // Smooth fade-in on page load
  document.body.style.opacity = 0;
  document.body.style.transition = "opacity 0.8s ease-in-out";
  setTimeout(() => (document.body.style.opacity = 1), 100);

  // Animate cards when scrolling into view
  const cards = document.querySelectorAll(".card");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = 1;
        entry.target.style.transform = "translateY(0)";
        entry.target.style.transition = "opacity 0.6s ease-out, transform 0.6s ease-out";
      }
    });
  }, { threshold: 0.2 });

  cards.forEach((card) => {
    card.style.opacity = 0;
    card.style.transform = "translateY(30px)";
    observer.observe(card);
  });

  // Add hover glow to table rows
  const rows = document.querySelectorAll("tbody tr");
  rows.forEach((row) => {
    row.addEventListener("mouseenter", () => {
      row.style.boxShadow = "0 4px 12px rgba(18,65,112,0.2)";
      row.style.transform = "scale(1.01)";
    });
    row.addEventListener("mouseleave", () => {
      row.style.boxShadow = "none";
      row.style.transform = "scale(1)";
    });
  });
});
