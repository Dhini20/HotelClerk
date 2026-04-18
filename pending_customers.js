// ../js/pending_customers.js
document.addEventListener('DOMContentLoaded', () => {
  const customersGrid = document.getElementById('customersGrid');
  const modal = document.getElementById('customerModal');
  const modalBox = modal.querySelector('div');
  const modalClose = document.getElementById('modalClose');
  const modalCancel = document.getElementById('modalCancel');
  const modalVerify = document.getElementById('modalVerify');
  const toastContainer = document.getElementById('toastContainer');

  let currentCustomerId = null;

  // Helper: compute base path to AJAX helper (so fetch works regardless of folder)
  const basePath = (() => {
    const p = window.location.pathname;
    return p.substring(0, p.lastIndexOf('/') + 1);
  })();

  // Helper: show toast
  function showToast(msg, success = true) {
    const node = document.createElement('div');
    node.className = 'px-4 py-2 rounded-lg shadow-lg text-sm ' + (success ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white');
    node.textContent = msg;
    toastContainer.appendChild(node);
    setTimeout(() => { node.style.opacity = '0'; node.style.transform = 'translateY(6px)'; }, 2200);
    setTimeout(() => node.remove(), 2600);
  }

  // Open modal with data
  function openModal(data) {
    currentCustomerId = data.CustomerID;
    document.getElementById('modalName').textContent = (data.FirstName || '') + ' ' + (data.LastName || '');
    document.getElementById('modalUsername').textContent = data.Username || '';
    document.getElementById('modalNIC').textContent = data.NIC || '—';
    document.getElementById('modalPhone').textContent = data.PhoneNo || '—';
    document.getElementById('modalEmail').textContent = data.Email || '—';
    document.getElementById('modalAddress').textContent = data.Address || '—';

    modal.classList.remove('hidden');
    // animate
    setTimeout(() => {
      modalBox.classList.add('scale-100', 'opacity-100');
      modalBox.style.transform = 'scale(1)';
      modalBox.style.opacity = '1';
    }, 20);
  }

  // Close modal
  function closeModal() {
    modalBox.classList.remove('scale-100', 'opacity-100');
    modalBox.style.transform = 'scale(.95)';
    modalBox.style.opacity = '0';
    setTimeout(() => modal.classList.add('hidden'), 220);
    currentCustomerId = null;
  }

  // Delegated click handler: view-btn or quick-verify
  document.body.addEventListener('click', async (e) => {
    const viewBtn = e.target.closest('.view-btn');
    const quickBtn = e.target.closest('.quick-verify');
    const card = e.target.closest('.customer-card');

    // View details (open modal)
    if (viewBtn) {
      const id = viewBtn.dataset.id;
      try {
        const res = await fetch(basePath + 'verify_customer.php?id=' + encodeURIComponent(id), { cache: 'no-store' });
        if (!res.ok) throw new Error('Failed to fetch customer.');
        const data = await res.json();
        openModal(data);
      } catch (err) {
        console.error(err);
        showToast('Unable to load details', false);
      }
      return;
    }

    // Quick verify directly from card
    if (quickBtn) {
      const id = quickBtn.dataset.id;
      if (!confirm('Verify this customer now?')) return;
      try {
        const res = await fetch(basePath + 'verify_customer.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(id)
        });
        const txt = await res.text();
        if (txt.trim() === 'success') {
          // remove card
          const el = document.querySelector('[data-id="' + id + '"]');
          if (el) el.remove();
          showToast('Customer verified');
        } else {
          showToast('Verify failed', false);
        }
      } catch (err) {
        console.error(err);
        showToast('Server error', false);
      }
      return;
    }

    // Click outside modal content closes it
    if (e.target === modal) closeModal();
  });

  // Modal buttons
  modalClose.addEventListener('click', closeModal);
  modalCancel.addEventListener('click', closeModal);

  modalVerify.addEventListener('click', async () => {
    if (!currentCustomerId) return;
    modalVerify.disabled = true;
    modalVerify.textContent = 'Verifying...';
    try {
      const res = await fetch(basePath + 'verify_customer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(currentCustomerId)
      });
      const txt = await res.text();
      if (txt.trim() === 'success') {
        // remove card
        const el = document.querySelector('[data-id="' + currentCustomerId + '"]');
        if (el) el.remove();
        showToast('Customer verified');
        closeModal();
      } else {
        showToast('Verify failed', false);
      }
    } catch (err) {
      console.error(err);
      showToast('Server error', false);
    } finally {
      modalVerify.disabled = false;
      modalVerify.textContent = 'Verify';
    }
  });

  // small reveal animation for cards
  document.querySelectorAll('.fade-in-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), 80 * i);
  });

  // Esc to close
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
  });
});
