// customer_signup.js
// Handle animations, scroll effects, hover effects

document.addEventListener("DOMContentLoaded", () => {
    // Fade in effect on page load
    const formCard = document.querySelector(".glass-card");
    formCard.style.opacity = 0;
    formCard.style.transform = "translateY(50px)";
    setTimeout(() => {
        formCard.style.transition = "all 1s ease";
        formCard.style.opacity = 1;
        formCard.style.transform = "translateY(0)";
    }, 200);

    // Scroll reveal for footer and header
    const revealElements = document.querySelectorAll("header, footer");
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-slideUp");
            }
        });
    }, { threshold: 0.2 });

    revealElements.forEach(el => observer.observe(el));

    // Hover glow for inputs
    document.querySelectorAll("input").forEach(input => {
        input.addEventListener("focus", () => {
            input.style.boxShadow = "0 0 15px rgba(0, 200, 255, 0.8)";
        });
        input.addEventListener("blur", () => {
            input.style.boxShadow = "none";
        });
    });
});

// Extra animations (CSS injected)
const style = document.createElement("style");
style.textContent = `
@keyframes fadeIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0);} }
.animate-fadeIn { animation: fadeIn 1s ease forwards; }

@keyframes slideUp { from { transform: translateY(50px); opacity: 0;} to { transform: translateY(0); opacity: 1;} }
.animate-slideUp { animation: slideUp 1s ease forwards; }
`;
document.head.appendChild(style);
