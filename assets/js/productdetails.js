// assets/js/productdetails.js
(function () {
  // --- helpers --------------------------------------------------------------
  function $(id){ return document.getElementById(id); }
  function getParam(name){
    const p = new URLSearchParams(location.search);
    return p.get(name);
  }
  function loadLS(key, fallback){
    try { return JSON.parse(localStorage.getItem(key)) ?? fallback; } catch { return fallback; }
  }
  function saveLS(key, value){
    localStorage.setItem(key, JSON.stringify(value));
  }

  // --- find product by id ---------------------------------------------------
  const id = Number(getParam('id'));
  const products = window.productsData || [];
  const product = products.find(p => p.id === id);

  if (!product) {
    // bad/missing id -> go back to products page
    location.href = 'products.html';
    return;
  }

  // --- populate details -----------------------------------------------------
  const nameEl = $('productName');
  const priceEl = $('productPrice');
  const descEl = $('productDescription');
  const imgEl = $('mainProductImage');

  if (nameEl) nameEl.textContent = product.name;
  if (priceEl) priceEl.textContent = `£${product.price.toFixed(2)}`;
  if (descEl) descEl.textContent = product.description || '';
  if (imgEl) imgEl.src = product.image || 'assets/images/placeholder.png';
  document.title = `${product.name} | SABIL Perfumes`;

  // If your thumbnails are static, they’ll keep working with changeImage().
  // If you later add multiple images per product, you can build the thumbnail
  // list here from product.images.

  // --- quantity controls ----------------------------------------------------
  const qtyInput = $('quantity');
  function clampQty(n){
    const num = Number(n) || 1;
    return Math.min(10, Math.max(1, num));
  }
  window.increaseQuantity = function(){
    if (!qtyInput) return;
    qtyInput.value = clampQty(Number(qtyInput.value) + 1);
  };
  window.decreaseQuantity = function(){
    if (!qtyInput) return;
    qtyInput.value = clampQty(Number(qtyInput.value) - 1);
  };

  // --- image swap (used by your thumbnail onclick) --------------------------
  window.changeImage = function(src){
    if ($('mainProductImage')) $('mainProductImage').src = src;
    // toggle 'active' class on thumbnails (optional)
    document.querySelectorAll('.image-thumbnails .thumbnail').forEach(t=>{
      t.classList.toggle('active', t.getAttribute('src') === src);
    });
  };

  // --- cart + favourites (localStorage) -------------------------------------
  function getCart(){ return loadLS('cart', []); }               // [{id, qty}]
  function setCart(c){ saveLS('cart', c); updateCartCount(); }

  function addToCartLS(prodId, qty){
    const cart = getCart();
    const i = cart.findIndex(x => x.id === prodId);
    if (i > -1) cart[i].qty += qty;
    else cart.push({ id: prodId, qty });
    setCart(cart);
  }

  function updateCartCount(){
    const count = getCart().reduce((a,b)=> a + (b.qty||0), 0);
    // If you add a badge somewhere, update it here. Example:
    // document.querySelector('#bagBtn')?.setAttribute('data-count', String(count));
  }

  function getFavs(){ return loadLS('favs', []); }               // [id, id, ...]
  function setFavs(f){ saveLS('favs', f); }
  function toggleFavLS(prodId){
    let favs = getFavs();
    favs = favs.includes(prodId) ? favs.filter(id => id !== prodId) : favs.concat(prodId);
    setFavs(favs);
    return favs.includes(prodId);
  }

  // --- wire buttons ---------------------------------------------------------
  window.addToCart = function(){
    const qty = clampQty(qtyInput ? qtyInput.value : 1);
    addToCartLS(product.id, qty);
    // Tiny feedback (replace with your toast/snackbar if you have one)
    alert(`${product.name} ×${qty} added to cart`);
  };

  window.buyNow = function(){
    const qty = clampQty(qtyInput ? qtyInput.value : 1);
    addToCartLS(product.id, qty);
    location.href = 'cart.html';
  };

  window.addToFavouritesDetails = function(){
    const isFav = toggleFavLS(product.id);
    // Update the button text/state if you want
    const btn = document.querySelector('.favourite-btn');
    if (btn) btn.textContent = isFav ? '♥ Favourited' : '♡ Favourite';
  };

  // Initial UI state for favourite button
  (function initFavBtn(){
    const btn = document.querySelector('.favourite-btn');
    if (!btn) return;
    const isFav = getFavs().includes(product.id);
    btn.textContent = isFav ? '♥ Favourited' : '♡ Favourite';
  })();

  // Ensure cart count is initialised on load
  updateCartCount();
})();
