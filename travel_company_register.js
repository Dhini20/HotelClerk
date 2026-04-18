// Page load animation
document.addEventListener("DOMContentLoaded", () => {
    const formCard = document.querySelector(".animate-slideIn");
    if (formCard) {
        formCard.style.opacity = "0";
        formCard.style.transform = "translateY(50px)";
        setTimeout(() => {
            formCard.style.transition = "all 0.8s ease-out";
            formCard.style.opacity = "1";
            formCard.style.transform = "translateY(0)";
        }, 200);
    }
});

// Hover floating effects for inputs and buttons
const inputs = document.querySelectorAll("input");
inputs.forEach(input => {
    input.addEventListener("focus", () => {
        input.style.boxShadow = "0 0 15px rgba(103,192,144,0.8)";
    });
    input.addEventListener("blur", () => {
        input.style.boxShadow = "none";
    });
});

const button = document.querySelector("button[type='submit']");
if (button) {
    button.addEventListener("mouseover", () => {
        button.style.boxShadow = "0 0 20px rgba(103,192,144,0.9)";
    });
    button.addEventListener("mouseout", () => {
        button.style.boxShadow = "none";
    });
}

// Smooth reveal on scroll
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = 1;
            entry.target.style.transform = "translateY(0)";
        }
    });
}, { threshold: 0.2 });

document.querySelectorAll("input, button").forEach(el => {
    el.style.opacity = 0;
    el.style.transform = "translateY(30px)";
    el.style.transition = "all 0.6s ease-out";
    observer.observe(el);
});
