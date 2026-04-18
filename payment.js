// ../js/payment.js
// Animations, interactions, card preview, and client-side validations for payment.php

document.addEventListener('DOMContentLoaded', () => {
  // Simple page-load animations
  document.querySelectorAll('.animate-fadeIn').forEach(el => {
    el.animate([{ opacity: 0, transform: 'translateY(10px)' }, { opacity: 1, transform: 'translateY(0)' }], { duration: 700, easing: 'ease-out', fill: 'forwards' });
  });
  document.querySelectorAll('.animate-slideUp').forEach(el => {
    el.animate([{ opacity: 0, transform: 'translateY(20px)' }, { opacity: 1, transform: 'translateY(0)' }], { duration: 900, easing: 'ease-out', fill: 'forwards' });
  });

  // Scroll reveal
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('opacity-100', 'translate-y-0');
      }
    });
  }, { threshold: 0.15 });

  document.querySelectorAll('section, aside, form').forEach(el => {
    el.classList.add('opacity-0', 'translate-y-6');
    observer.observe(el);
  });

  // Floating decorative shapes respond to mouse movement
  const shape1 = document.getElementById('shape1');
  const shape2 = document.getElementById('shape2');
  document.addEventListener('mousemove', (e) => {
    const x = (e.clientX / window.innerWidth) - 0.5;
    const y = (e.clientY / window.innerHeight) - 0.5;
    if (shape1) shape1.style.transform = `translate(${x * 30}px, ${y * 30}px)`;
    if (shape2) shape2.style.transform = `translate(${x * -20}px, ${y * -20}px)`;
  });

  // Card preview elements
  const cardNumberInput = document.getElementById('cardnumber');
  const cardNameInput   = document.getElementById('cardholder');
  const cardTypeSelect  = document.getElementById('cardtype');
  const expMonthSelect  = document.getElementById('expmonth');
  const expYearSelect   = document.getElementById('expyear');
  const cvvInput        = document.getElementById('cvv');

  const previewNumber = document.getElementById('previewNumber');
  const previewName   = document.getElementById('previewName');
  const previewType   = document.getElementById('previewType');
  const previewExp    = document.getElementById('previewExp');
  const hiddenTotal = document.getElementById('hiddenTotalFee'); // may not be present on this page

  // Format card number visually (grouped)
  function formatCardNumber(value) {
    const digits = value.replace(/\D/g, '').substring(0,19);
    return digits.replace(/(\d{4})(?=\d)/g, '$1 ');
  }

  cardNumberInput && cardNumberInput.addEventListener('input', (e) => {
    const formatted = formatCardNumber(e.target.value);
    e.target.value = formatted;
    previewNumber.textContent = formatted ? formatted : '•••• •••• •••• ••••';
  });

  cardNameInput && cardNameInput.addEventListener('input', (e) => {
    previewName.textContent = e.target.value ? e.target.value : 'Full Name';
  });

  cardTypeSelect && cardTypeSelect.addEventListener('change', (e) => {
    previewType.textContent = e.target.value ? e.target.value : '--';
  });

  function updateExpPreview() {
    const m = expMonthSelect ? expMonthSelect.value : '';
    const y = expYearSelect ? expYearSelect.value : '';
    previewExp.textContent = (m && y) ? `${m.padStart(2, '0')} / ${y}` : 'MM / YYYY';
  }
  expMonthSelect && expMonthSelect.addEventListener('change', updateExpPreview);
  expYearSelect && expYearSelect.addEventListener('change', updateExpPreview);

  // Simple input focus micro-animation
  document.querySelectorAll('input, select').forEach(inp => {
    inp.addEventListener('focus', () => inp.classList.add('ring-4', 'ring-[#67C090]/30', 'scale-101'));
    inp.addEventListener('blur', () => inp.classList.remove('ring-4', 'ring-[#67C090]/30', 'scale-101'));
  });

  // Basic client-side validation before submit
  const paymentForm = document.getElementById('paymentForm');
  if (paymentForm) {
    paymentForm.addEventListener('submit', (ev) => {
      // Minimal check: required fields non-empty and basic lengths
      const name = cardNameInput ? cardNameInput.value.trim() : '';
      const number = cardNumberInput ? cardNumberInput.value.replace(/\s+/g, '') : '';
      const type = cardTypeSelect ? cardTypeSelect.value : '';
      const month = expMonthSelect ? parseInt(expMonthSelect.value) : 0;
      const year = expYearSelect ? parseInt(expYearSelect.value) : 0;
      const cvv = cvvInput ? cvvInput.value.trim() : '';

      const clientErrors = [];
      if (!name) clientErrors.push("Cardholder name required.");
      if (!number || number.length < 13 || number.length > 19) clientErrors.push("Card number length seems invalid.");
      if (!type) clientErrors.push("Card type required.");
      if (!month || month < 1 || month > 12) clientErrors.push("Expiration month invalid.");
      const curYear = new Date().getFullYear();
      if (!year || year < curYear || year > curYear + 20) clientErrors.push("Expiration year invalid.");
      if (!/^\d{3,4}$/.test(cvv)) clientErrors.push("CVV must be 3 or 4 digits.");

      if (clientErrors.length) {
        ev.preventDefault();
        alert("Please correct the following:\n- " + clientErrors.join("\n- "));
        return false;
      }
      // allow form to submit
      return true;
    });
  }

  // Small safe-style animations for card preview hover
  const cardPreview = document.getElementById('cardPreview');
  if (cardPreview) {
    cardPreview.addEventListener('mousemove', (e) => {
      const r = cardPreview.getBoundingClientRect();
      const px = (e.clientX - r.left) / r.width - 0.5;
      const py = (e.clientY - r.top) / r.height - 0.5;
      cardPreview.style.transform = `perspective(800px) rotateY(${px * 6}deg) rotateX(${-py * 6}deg) translateZ(0)`;
      cardPreview.style.transition = 'transform 0.07s linear';
    });
    cardPreview.addEventListener('mouseleave', () => {
      cardPreview.style.transform = `perspective(800px) rotateY(0deg) rotateX(0deg)`;
      cardPreview.style.transition = 'transform 0.3s ease';
    });
  }

});
