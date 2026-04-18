// Show loader and fade in dashboard
window.addEventListener('load', () => {
    const loader = document.getElementById('pageLoader');
    loader.style.opacity = 0;
    setTimeout(() => loader.style.display = 'none', 600);

    // Reveal animations
    const reveals = document.querySelectorAll('.reveal');
    reveals.forEach(el => el.classList.add('show'));
});

// Admin dropdown toggle (if exists)
const adminBtn = document.getElementById('adminBtn');
const adminDropdown = document.getElementById('adminDropdown');
if(adminBtn && adminDropdown){
    adminBtn.addEventListener('click', () => {
        adminDropdown.classList.toggle('hidden');
    });
}

// Scroll-based reveal
const scrollReveal = () => {
    const reveals = document.querySelectorAll('.reveal');
    for(let i=0; i<reveals.length; i++){
        const windowHeight = window.innerHeight;
        const elementTop = reveals[i].getBoundingClientRect().top;
        const revealPoint = 150;
        if(elementTop < windowHeight - revealPoint){
            reveals[i].classList.add('show');
        }
    }
}
window.addEventListener('scroll', scrollReveal);

// Sidebar hover glow animation
const sidebarItems = document.querySelectorAll('.sidebar .item');
sidebarItems.forEach(item => {
    item.addEventListener('mouseenter', () => {
        item.style.boxShadow = "0 8px 25px rgba(103,192,144,0.35)";
    });
    item.addEventListener('mouseleave', () => {
        item.style.boxShadow = "none";
    });
});
