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


if (!product) {
  console.log("Product not found:", pid);
  console.log(window.productsData);
  return;
}
console.log("Loaded product:", product);
console.log("Image path:", product.image);
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
  const CART_KEY = 'cart';

function getCart() {
  return LS.get(CART_KEY, []);
}

function setCart(cart) {
  LS.set(CART_KEY, cart);
}

function addToCartInternal(product, qty) {
  qty = clamp(qty);
  const cart = getCart();
  const existingItem = cart.find(item => item.id === product.id);

  if (existingItem) {
    existingItem.quantity = clamp((existingItem.quantity || 1) + qty);
  } else {
    cart.push({
      id: product.id,
      name: product.name,
      price: product.price,
      image: product.image,
      category: product.category || '',
      quantity: qty
    });
  }

  setCart(cart);
}

  // Public handlers used by your HTML
 window.addToCart = () => {
  const q = clamp(qtyIn ? qtyIn.value : 1);
  addToCartInternal(product, q);
  alert(`${product.name} ×${q} added to cart`);
};
  window.buyNow = () => {
  const q = clamp(qtyIn ? qtyIn.value : 1);
  addToCartInternal(product, q);
  location.href = 'cart.php';
};

// ----- FAVOURITES (server-backed via favourites.php) -----
const FAV_ENDPOINT = 'favourites.php';
const favBtn = document.getElementById('favBtnDetails');

function setFavUI(on){
  if (!favBtn) return;
  favBtn.textContent = on ? '♥ Favourited' : '♡ Favourite';
  favBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
  favBtn.classList.toggle('is-on', on); // for styling/highlight
}

function toast(msg){
  // simple 1-message toast (no duplicates)
  const old = document.querySelector('.fav-toast');
  if (old) old.remove();

  const t = document.createElement('div');
  t.className = 'fav-toast';
  t.textContent = msg;
  document.body.appendChild(t);

  setTimeout(()=> t.remove(), 1800);
}

async function postFav(payload){
  const res = await fetch(FAV_ENDPOINT, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams(payload),
    credentials: 'same-origin'
  });
  const text = await res.text();
  try { return JSON.parse(text); }
  catch { return { status: "error", message: "Data is not valid" }; }
}

// Initialise current state
if (favBtn){
  (async () => {
    const data = await postFav({ action: 'list' });
    if (data.requireLogin) { setFavUI(false); return; }

    const ids = data.favourites || [];
    setFavUI(ids.map(Number).includes(product.id));
  })();

  favBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    e.stopPropagation();

    favBtn.disabled = true;
    try {
      const res = await postFav({ action: 'toggle', product_id: product.id });

      if (res.requireLogin) {
        toast("Please login to add products to favourites.");
        window.location.href = res.loginUrl || 'login.html';
        return;
      }

      if (res.status === 'success') {
        setFavUI(!!res.favourited);
        toast(res.message || (res.favourited ? "Saved as favourite." : "Removed from favourites."));
      } else {
        toast(res.message || "Could not update favourites.");
      }
    } finally {
      favBtn.disabled = false;
    }
  });
}
})();