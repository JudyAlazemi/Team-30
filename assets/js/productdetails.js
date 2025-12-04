// assets/js/productdetails.js
(() => {
  'use strict';

  // ----- tiny helpers -----
  const $ = (sel, root = document) => root.querySelector(sel);
  const fmt = n => `£${(Math.round(n * 100) / 100).toFixed(2)}`;

  // ----- get product from ?id= -----
  const params = new URLSearchParams(location.search);
  const pid = Number(params.get('id') || 0);
  const products = window.productsData || [];
  const product = products.find(p => p.id === pid);

  if (!product) { location.replace('products.html'); return; }

  // ----- fill UI -----
  const elName  = $('#productName');
  const elPrice = $('#productPrice');
  const elDesc  = $('#productDescription');
  const elImg   = $('#mainProductImage');
  const qtyIn   = $('#quantity');

  if (elName)  elName.textContent  = product.name;
  if (elPrice) elPrice.textContent = fmt(product.price);
  if (elDesc)  elDesc.textContent  = product.description || '';
  if (elImg)  { elImg.src = product.image || elImg.src; elImg.alt = product.name; }
  document.title = `${product.name} | SABIL Perfumes`;

  // Thumbnails (optional: works with your existing onclick="changeImage(...)")
  window.changeImage = (src) => {
    if (!src || !elImg) return;
    elImg.src = src;
    document.querySelectorAll('.image-thumbnails .thumbnail')
      .forEach(t => t.classList.toggle('active', t.getAttribute('src') === src));
  };

  // ----- quantity controls (used by inline +/- buttons) -----
  function clamp(n){ n = Number(n)||1; return Math.min(10, Math.max(1, n)); }
  window.increaseQuantity = () => { if (qtyIn) qtyIn.value = clamp((+qtyIn.value||1) + 1); };
  window.decreaseQuantity = () => { if (qtyIn) qtyIn.value = clamp((+qtyIn.value||1) - 1); };

  // ----- cart (localStorage) -----
  const LS = {
    get(k, fb){ try { return JSON.parse(localStorage.getItem(k)) ?? fb; } catch { return fb; } },
    set(k, v){ localStorage.setItem(k, JSON.stringify(v)); }
  };
  const CART_KEY = 'cart'; // [{id, qty}]

  function getCart(){ return LS.get(CART_KEY, []); }
  function setCart(c){ LS.set(CART_KEY, c); }
  function addToCartInternal(id, qty){
    qty = clamp(qty);
    const cart = getCart();
    const i = cart.findIndex(x => x.id === id);
    if (i === -1) cart.push({ id, qty });
    else cart[i].qty = clamp((cart[i].qty||1) + qty);
    setCart(cart);
  }

  // Public handlers used by your HTML
  window.addToCart = () => {
    const q = clamp(qtyIn ? qtyIn.value : 1);
    addToCartInternal(product.id, q);
    alert(`${product.name} ×${q} added to cart`);
  };
  window.buyNow = () => {
    const q = clamp(qtyIn ? qtyIn.value : 1);
    addToCartInternal(product.id, q);
    location.href = 'cart.html';
  };

 // ----- FAVOURITES (server-backed via favourites.php) -----
const FAV_ENDPOINT = 'favourites.php';
const favBtn = document.getElementById('favBtnDetails');

function setFavUI(on){
  if (!favBtn) return;
  favBtn.textContent = on ? '♥ Favourited' : '♡ Favourite';
  favBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
  favBtn.classList.toggle('is-on', on);
}

async function favList(){
  const r = await fetch(FAV_ENDPOINT, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({ action: 'list' })
  });
  if (!r.ok) throw new Error('HTTP ' + r.status);
  const j = await r.json();
  return j.favourites || [];
}

async function favToggle(productId){
  const r = await fetch(FAV_ENDPOINT, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({ action: 'toggle', product_id: productId })
  });
  if (!r.ok) throw new Error('HTTP ' + r.status);
  return await r.json(); // {status:'success', favourited:true/false}
}

// Initialise current state
if (favBtn){
  (async () => {
    try {
      const ids = await favList();
      setFavUI(ids.includes(product.id));
    } catch (e) {
      console.warn('Fav init failed:', e);
      setFavUI(false);
    }
  })();

  favBtn.addEventListener('click', async (e) => {
    e.preventDefault();          // <-- stop accidental navigation
    e.stopPropagation();         // <-- stop bubbling into parent links/overlays
    favBtn.disabled = true;
    try {
      const res = await favToggle(product.id);
      if (res.status === 'success') setFavUI(!!res.favourited);
      else alert(res.message || 'Could not update favourites.');
    } catch (err){
      alert('Could not update favourites. Please try again.');
      console.error(err);
    } finally {
      favBtn.disabled = false;
    }
  });
}
})(); 