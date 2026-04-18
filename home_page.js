/* home_page.js
   Handles:
   - Typewriter headline
   - Page load & scroll reveal animations (IntersectionObserver)
   - Simple tilt/tilt-on-hover micro-interactions
   - Hero parallax based on mouse movement (decorative)
   - Mobile drawer toggle
   - Small hover glows for nav / CTA
*/

(() => {
  'use strict';

  /* Helper: query selector */
  const $ = (s, root = document) => root.querySelector(s);
  const $$ = (s, root = document) => Array.from(root.querySelectorAll(s));

  /* Typewriter (simple, non-blocking) */
  function initTypewriter() {
    const wrap = document.querySelector('.typewriter > .wrap');
    if (!wrap) return;
    const text = wrap.textContent.trim();
    wrap.textContent = '';
    let i = 0;
    const speed = 60;
    const interval = setInterval(() => {
      wrap.textContent += text.charAt(i);
      i++;
      if (i >= text.length) clearInterval(interval);
    }, speed);
  }

  /* Reveal on scroll */
  function initScrollReveal() {
    const revealEls = $$('.reveal');
    if (!revealEls.length) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          // small stagger via style attribute if data-delay present
          const d = entry.target.dataset.delay;
          if (d) {
            entry.target.style.transitionDelay = (d / 1000) + 's';
          }
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    revealEls.forEach(el => io.observe(el));
  }

  /* Simple tilt effect on elements with [data-tilt] or .tilt on hover */
  function initTilt() {
    const tiltEls = $$('[data-tilt], .tilt');
    tiltEls.forEach(el => {
      el.style.transformStyle = 'preserve-3d';
      el.addEventListener('mousemove', (e) => {
        const rect = el.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;
        const dx = (e.clientX - cx) / rect.width;
        const dy = (e.clientY - cy) / rect.height;
        const rotateX = (dy * 6).toFixed(2);
        const rotateY = (dx * -8).toFixed(2);
        el.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(6px)`;
      });
      el.addEventListener('mouseleave', () => {
        el.style.transform = '';
      });
      el.addEventListener('mouseenter', () => {
        el.style.transition = 'transform 180ms ease';
        setTimeout(() => el.style.transition = '', 200);
      });
    });
  }

  /* Hero parallax: subtle movement on mouse for decorative shapes (non-animated by CSS) */
  function initHeroParallax() {
    const hero = document.querySelector('main section');
    if (!hero) return;
    hero.addEventListener('mousemove', (e) => {
      const rect = hero.getBoundingClientRect();
      const px = (e.clientX - rect.left) / rect.width - 0.5;
      const py = (e.clientY - rect.top) / rect.height - 0.5;
      const svgs = hero.querySelectorAll('svg');
      svgs.forEach((svg, i) => {
        const depth = (i % 2 === 0) ? 6 : 10;
        svg.style.transform = `translate(${px * depth}px, ${py * depth}px)`;
        svg.style.transition = 'transform 200ms linear';
      });
    });
    hero.addEventListener('mouseleave', () => {
      hero.querySelectorAll('svg').forEach(svg => svg.style.transform = '');
    });
  }

  /* Mobile drawer controls */
  function initMobileDrawer() {
    const btn = $('#mobileMenuBtn');
    const drawer = $('#mobileDrawer');
    const close = $('#closeDrawer');
    if (!btn || !drawer) return;
    btn.addEventListener('click', () => drawer.classList.remove('hidden'));
    close.addEventListener('click', () => drawer.classList.add('hidden'));
    // close when clicking backdrop
    drawer.addEventListener('click', (ev) => {
      if (ev.target === drawer) drawer.classList.add('hidden');
    });
  }

  /* Nav hover micro-interactions */
  function initNavHoverEffects() {
    const navItems = $$('.nav-item');
    navItems.forEach(item => {
      item.addEventListener('mouseenter', () => {
        item.style.transform = 'translateY(-3px)';
        item.style.transition = 'transform 180ms ease';
      });
      item.addEventListener('mouseleave', () => {
        item.style.transform = '';
      });
    });

    // CTA hover: add subtle shadow/glow
    const cta = document.querySelectorAll('.cta-btn');
    cta.forEach(btn => {
      btn.addEventListener('mouseenter', () => {
        btn.style.boxShadow = '0 12px 40px rgba(58,160,255,0.18)';
        btn.style.transform = 'translateY(-4px) scale(1.02)';
      });
      btn.addEventListener('mouseleave', () => {
        btn.style.boxShadow = '';
        btn.style.transform = '';
      });
    });
  }

  /* Small keyboard accessibility: focus outlines for interactive elements (fallback) */
  function initFocusOutline() {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        document.body.classList.add('show-focus');
      }
    });
  }

  /* Initialize all */
  function init() {
    initTypewriter();
    initScrollReveal();
    initTilt();
    initHeroParallax();
    initMobileDrawer();
    initNavHoverEffects();
    initFocusOutline();

    // Small page-load entrance: fade in main
    document.documentElement.style.scrollBehavior = 'smooth';
    document.body.style.opacity = '0';
    setTimeout(() => { document.body.style.transition = 'opacity 600ms ease'; document.body.style.opacity = ''; }, 80);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Background canvas animation
const canvas = document.getElementById("bgCanvas");
if (canvas) {
  const ctx = canvas.getContext("2d");
  let particles = [];
  let orbCount = 10;

  function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  resizeCanvas();
  window.addEventListener("resize", resizeCanvas);


  function animate() {
    drawParticles();
    requestAnimationFrame(animate);
  }
  animate();
}

})();
