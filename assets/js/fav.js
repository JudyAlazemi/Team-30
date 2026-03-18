document.addEventListener('DOMContentLoaded', () => {
  const FAV_URL = 'favourites.php';

  const grid = document.getElementById('favourites-grid');
  const emptyState = document.getElementById('empty-state');

  function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, m => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[m]));
  }

  async function postFav(payload) {
    const res = await fetch(FAV_URL, {
      method: 'POST',
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams(payload),
      credentials: 'same-origin'
    });

    const text = await res.text();
    let data;
    try { data = JSON.parse(text); }
    catch { data = { status: "error", message: "Data is not valid" }; }

    return data;
  }

  function showGuestMessage() {
    if (!emptyState) return;
    emptyState.style.display = 'block';
    emptyState.innerHTML = `
      <p><strong>You need to login</strong> to choose a favourite.</p>
      <p><a href="login.html" class="hero-btn">Sign in</a></p>
    `;
    if (grid) grid.innerHTML = '';
  }

  function showEmptyCustomerMessage() {
    if (!emptyState) return;
    emptyState.style.display = 'block';
    emptyState.innerHTML = `
      <p><strong>No favourites yet.</strong></p>
      <p>Please favourite a product to see it here.</p>
      <p><a href="products.php" class="hero-btn">Go to Products</a></p>
    `;
    if (grid) grid.innerHTML = '';
  }

  function hideEmptyState() {
    if (emptyState) emptyState.style.display = 'none';
  }

  function renderFavourites(ids) {
    if (!grid) return;

    const data = window.productsData || []; // from products-data.js
    const favProducts = (ids || []).map(id => data.find(p => Number(p.id) === Number(id))).filter(Boolean);

    if (!favProducts.length) {
      showEmptyCustomerMessage();
      return;
    }

    hideEmptyState();

    grid.innerHTML = favProducts.map(p => `
      <div class="product-card" style="max-width:320px;margin:0 auto;">
        <div class="product-image">
          <img src="${escapeHtml(p.image)}" alt="${escapeHtml(p.name)}">
          <div class="product-overlay">
            <a href="productdetails.php?id=${encodeURIComponent(p.id)}" class="hero-btn">View Details</a>
          </div>
        </div>
        <div class="product-info">
          <h3>${escapeHtml(p.name)}</h3>
          <p class="product-description">${escapeHtml(p.description)}</p>
          <p class="product-price">£${Number(p.price || 0).toFixed(2)}</p>

          <button class="hero-btn fav-remove" data-product-id="${escapeHtml(p.id)}" style="background:#fff;color:#4e4138;border:2px solid #4e4138;">
            Remove
          </button>
        </div>
      </div>
    `).join('');
  }

  async function loadFavourites() {
    const data = await postFav({ action: "list" });

    // Guest blocked by backend
    if (data.requireLogin) {
      showGuestMessage();
      return;
    }

    if (data.status !== "success") {
      // fallback
      if (emptyState) {
        emptyState.style.display = 'block';
        emptyState.innerHTML = `<p>${escapeHtml(data.message || 'Could not load favourites')}</p>`;
      }
      return;
    }

    renderFavourites(data.favourites || []);
  }

  // Remove button handler
  document.body.addEventListener('click', async (e) => {
    const btn = e.target.closest('.fav-remove');
    if (!btn) return;

    const productId = btn.dataset.productId;
    btn.disabled = true;

    try {
      const res = await postFav({ action: "toggle", product_id: productId });

      if (res.requireLogin) {
        alert("You need to login to choose a favourite.");
        window.location.href = res.loginUrl || 'login.html';
        return;
      }

      // Reload list after toggle
      await loadFavourites();
    } finally {
      btn.disabled = false;
    }
  });

  loadFavourites();
});