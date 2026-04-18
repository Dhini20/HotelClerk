// ../js/pending_reservations.js
document.addEventListener('DOMContentLoaded', () => {
  const loader = document.getElementById('pageLoader');
  if (loader) {
    setTimeout(() => {
      loader.style.opacity = 0;
      setTimeout(() => loader.remove(), 500);
    }, 450);
  }

  // reveal cards
  document.querySelectorAll('.reveal').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 80);
  });

  // compute base path
  const basePath = (() => {
    const p = window.location.pathname;
    return p.substring(0, p.lastIndexOf('/') + 1);
  })();

  // Quick verify via ajax (delegation)
  document.body.addEventListener('click', async (e) => {
    const btn = e.target.closest('.quick-verify');
    if (!btn) return;
    const id = btn.dataset.id;
    if (!id) return;
    if (!confirm('Verify this reservation now?')) return;

    btn.disabled = true;
    btn.textContent = 'Verifying...';

    try {
      const res = await fetch(basePath + 'verify_reservation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
      });
      const txt = await res.text();
      if (txt.trim() === 'success') {
        // remove card
        const el = document.querySelector('[data-id="' + id + '"]');
        if (el) el.remove();
        showToast('Reservation verified', true);
      } else {
        showToast('Verify failed', false);
      }
    } catch (err) {
      console.error(err);
      showToast('Server error', false);
    } finally {
      // restore button if still present
      if (btn) { btn.disabled = false; btn.textContent = 'Quick Verify'; }
    }
  });

  // small toast helper
  function showToast(message, ok = true) {
    const node = document.createElement('div');
    node.textContent = message;
    node.className = 'fixed bottom-6 right-6 p-3 rounded-lg text-sm shadow-lg';
    node.style.background = ok ? 'rgba(103,192,144,0.95)' : 'rgba(239,68,68,0.95)';
    node.style.color = 'white';
    document.body.appendChild(node);
    setTimeout(() => { node.style.opacity = '0'; node.style.transform = 'translateY(8px)'; }, 1800);
    setTimeout(() => node.remove(), 2300);
  }
});
