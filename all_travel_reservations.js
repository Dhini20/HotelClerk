document.addEventListener("DOMContentLoaded", () => {
    // Page fade-in animation
    document.body.style.opacity = 0;
    document.body.style.transition = "opacity 1s ease-in-out";
    setTimeout(() => (document.body.style.opacity = 1), 100);

    // Fade-in elements
    const fadeElements = document.querySelectorAll(".animate-fadeIn, .animate-fadeUp");
    fadeElements.forEach((el, index) => {
        el.style.opacity = 0;
        el.style.transform = "translateY(30px)";
        el.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        setTimeout(() => {
            el.style.opacity = 1;
            el.style.transform = "translateY(0)";
        }, 200 * index);
    });

    // Hover floating effect for cards
    const cards = document.querySelectorAll(".glass-card");
    cards.forEach((card) => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-8px) scale(1.02)";
            card.style.transition = "transform 0.3s ease, box-shadow 0.3s ease";
        });
        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0) scale(1)";
        });
    });

    // Scroll animation observer
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = "translateY(0)";
                }
            });
        },
        { threshold: 0.2 }
    );

    document.querySelectorAll(".animate-fadeUp").forEach((el) => {
        el.style.opacity = 0;
        el.style.transform = "translateY(30px)";
        el.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        observer.observe(el);
    });
});
