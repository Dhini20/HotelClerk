// TravelCompany_Login.js

document.addEventListener("DOMContentLoaded", () => {
    const loginCard = document.getElementById("login-card");

    // Page load animation
    loginCard.classList.add("opacity-0", "translate-y-10");
    setTimeout(() => {
        loginCard.classList.remove("opacity-0", "translate-y-10");
        loginCard.classList.add("transition", "duration-1000", "ease-out", "opacity-100", "translate-y-0");
    }, 200);

    // Input hover effects
    const inputs = document.querySelectorAll("input");
    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.classList.add("shadow-xl", "scale-105");
        });
        input.addEventListener("blur", () => {
            input.classList.remove("shadow-xl", "scale-105");
        });
    });

    const btn = document.querySelector("button[type='submit']");
    btn.addEventListener("mouseover", () => {
        btn.classList.add("scale-105", "shadow-2xl");
    });
    btn.addEventListener("mouseleave", () => {
        btn.classList.remove("scale-105", "shadow-2xl");
    });

    // Floating orbs
    for (let i = 0; i < 6; i++) {
        const orb = document.createElement("div");
        orb.className = "absolute rounded-full bg-white/20 blur-2xl";
        orb.style.width = `${Math.random() * 80 + 40}px`;
        orb.style.height = orb.style.width;
        orb.style.top = `${Math.random() * 100}%`;
        orb.style.left = `${Math.random() * 100}%`;
        orb.style.transition = "transform 10s ease-in-out";
        document.body.appendChild(orb);

        setInterval(() => {
            orb.style.transform = `translate(${Math.random() * 50 - 25}px, ${Math.random() * 50 - 25}px)`;
        }, 5000);
    }
});
