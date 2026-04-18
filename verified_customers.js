document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const customersGrid = document.getElementById('customersGrid');
  const modal = document.getElementById('customerModal');
  const modalBox = modal ? modal.querySelector('div') : null;
  const modalClose = document.getElementById('modalClose');
  const modalCancel = document.getElementById('modalCancel');
  const toastContainer = document.getElementById('toastContainer');

  let currentCustomerId = null;

  // Build absolute URLs robustly (works in any subfolder)
  const urlFor = (path) => new URL(path, window.location.href).toString();

  function showToast(msg, success = true) {
    if (!toastContainer) return;
    const node = document.createElement('div');
    node.className = 'px-4 py-2 rounded-lg shadow-lg text-sm ' + (success ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white');
    node.textContent = msg;
    toastContainer.appendChild(node);
    setTimeout(() => { node.style.opacity = '0'; node.style.transform = 'translateY(6px)'; }, 2200);
    setTimeout(() => node.remove(), 2600);
  }

  // Open modal
  function openModal(data) {
    if (!modal || !modalBox) return;
    currentCustomerId = data.CustomerID;
    document.getElementById('modalName').textContent = (data.FirstName || '') + ' ' + (data.LastName || '');
    document.getElementById('modalUsername').textContent = data.Username || '';
    document.getElementById('modalNIC').textContent = data.NIC || '—';
    document.getElementById('modalPhone').textContent = data.PhoneNo || '—';
    document.getElementById('modalEmail').textContent = data.Email || '—';
    document.getElementById('modalAddress').textContent = data.Address || '—';

    modal.classList.remove('hidden');
    setTimeout(() => {
      modalBox.classList.add('scale-100', 'opacity-100');
      modalBox.style.transform = 'scale(1)';
      modalBox.style.opacity = '1';
    }, 20);
  }

  function closeModal() {
    if (!modal || !modalBox) return;
    modalBox.classList.remove('scale-100', 'opacity-100');
    modalBox.style.transform = 'scale(.95)';
    modalBox.style.opacity = '0';
    setTimeout(() => modal.classList.add('hidden'), 220);
  }

  // Handle clicks (view / delete)
  document.body.addEventListener('click', async (e) => {
    const viewBtn = e.target.closest('.view-btn');
    const delBtn = e.target.closest('.delete-btn');

    // View details
    if (viewBtn) {
      const id = viewBtn.dataset.id;
      try {
        const res = await fetch(urlFor('verify_customer.php?id=' + encodeURIComponent(id)), { cache: 'no-store' });
        if (!res.ok) throw new Error('Failed to load details');
        const data = await res.json();
        openModal(data);
      } catch (err) {
        console.error(err);
        showToast('Failed to load customer', false);
      }
      return;
    }

    // Delete customer
    if (delBtn) {
      const id = delBtn.dataset.id;
      if (!confirm('Are you sure you want to delete this customer?')) return;
      try {
        const res = await fetch(urlFor('delete_customer.php'), {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(id)
        });
        const txt = await res.text();
        if (txt.trim() === 'success') {
          const card = document.querySelector('[data-id="' + id + '"]');
          if (card) card.remove();
          showToast('Customer deleted');
        } else {
          showToast('Delete failed', false);
        }
      } catch (err) {
        console.error(err);
        showToast('Server error', false);
      }
      return;
    }

    if (modal && e.target === modal) closeModal();
  });

  if (modalClose) modalClose.addEventListener('click', closeModal);
  if (modalCancel) modalCancel.addEventListener('click', closeModal);

  // Debounce helper
  const debounce = (fn, ms = 250) => {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn.apply(null, args), ms);
    };
  };

  // Search function (AJAX)
  const performSearch = async () => {
    if (!customersGrid) return;
    const q = searchInput ? searchInput.value.trim() : '';
    try {
      const res = await fetch(urlFor('search_customers.php?q=' + encodeURIComponent(q)), { cache: 'no-store' });
      const html = await res.text();
      customersGrid.innerHTML = html;
      // Re-apply feather icons for newly injected nodes
      if (window.feather && typeof window.feather.replace === 'function') {
        window.feather.replace();
      }
      // small reveal animation
      customersGrid.querySelectorAll('.fade-in-up').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), 60 * i);
      });
    } catch (err) {
      console.error(err);
      customersGrid.innerHTML = '<div class="glass p-10 rounded-2xl text-center col-span-full text-white/60">Search error.</div>';
      showToast('Search error', false);
    }
  };

  if (searchInput) {
    searchInput.addEventListener('input', debounce(performSearch, 250));
  }

  // Initial animation for pre-rendered cards
  document.querySelectorAll('.fade-in-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), 80 * i);
  });
});
