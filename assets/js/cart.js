// assets/js/cart.js
// Renders cart from localStorage + productsData

// ---- tiny LS helpers ----
const LS = {
  get(k, fb){
    try { return JSON.parse(localStorage.getItem(k)) ?? fb; }
    catch { return fb; }
  },
  set(k, v){ localStorage.setItem(k, JSON.stringify(v)); }
};
const CART_KEY = 'cart'; // [{id, qty}]

function getCart(){ return LS.get(CART_KEY, []); }
function setCart(c){ LS.set(CART_KEY, c); }

// ---- merge cart with products ----
function mergeCart(){
  const cart = getCart();
  const products = (window.productsData || []);
  return cart.map(ci=>{
    const p = products.find(x=>x.id === ci.id);
    return p ? { ...p, qty: ci.qty } : null;
  }).filter(Boolean);
}

// ---- money helpers ----
const fmt = (n)=> `£${(Math.round(n*100)/100).toFixed(2)}`;
function totals(items){
  const subtotal = items.reduce((s,i)=> s + i.price * i.qty, 0);
  const shipping = subtotal >= 150 ? 0 : (items.length ? 10 : 0);
  const tax = subtotal * 0.08;
  const total = subtotal + shipping + tax;
  return { subtotal, shipping, tax, total };
}

// ---- DOM refs ----
const elList = document.getElementById('cart-list');
const elSubtotal = document.getElementById('subtotal');
const elShipping = document.getElementById('shipping');
const elTax = document.getElementById('tax');
const elTotal = document.getElementById('total');
const elCount = document.getElementById('item-count');
const elFSN = document.getElementById('fsn'); // optional “free shipping notice”

console.log('Cart.js loaded');
console.log('Cart list element:', elList);

// ---- actions ----
function updateQty(id, nextQty){
  const qty = Math.max(1, Math.min(10, Number(nextQty)||1));
  const cart = getCart().map(item => item.id===id ? {...item, qty} : item);
  setCart(cart);
  render();
}

function removeItem(id){
  const cart = getCart().filter(item => item.id !== id);
  setCart(cart);
  render();
}

// ---- rendering ----
function render(){
  console.log('Rendering cart...');
  const items = mergeCart();

  if (elCount) elCount.textContent = `${items.length} item${items.length!==1?'s':''} in your cart`;

  if (!items.length){
    if (elList){
      elList.innerHTML = `
        <div class="empty-cart">
          <p>Your cart is empty</p>
          <button class="btn-primary" type="button" style="max-width:300px;margin:0 auto;" onclick="location.href='products.php'">
            Continue Shopping
          </button>
        </div>`;
    }
    if (elSubtotal) elSubtotal.textContent = fmt(0);
    if (elShipping) elShipping.textContent = fmt(0);
    if (elTax) elTax.textContent = fmt(0);
    if (elTotal) elTotal.textContent = fmt(0);
    if (elFSN) elFSN.style.display = 'none';
    return;
  }

  // =====================  THIS PART MATCHES YOUR CSS  ======================
  if (elList){
    elList.innerHTML = items.map(it => `
      <div class="cart-item">
        <div class="item-image">
          <img src="${it.image}" alt="${it.name}">
        </div>

        <div class="item-details">
          <h3>${it.name}</h3>
          <p class="item-price">${fmt(it.price)}</p>

          <div class="quantity-controls">
            <button class="quantity-btn" type="button" data-action="dec" data-id="${it.id}">−</button>
            <span class="qty-display" data-id="${it.id}">${it.qty}</span>
            <button class="quantity-btn" type="button" data-action="inc" data-id="${it.id}">+</button>
          </div>

          <button class="remove-btn" type="button" data-action="remove" data-id="${it.id}">Remove</button>
        </div>

        <div class="item-right">
          <p>${fmt(it.price * it.qty)}</p>
        </div>
      </div>
    `).join('');
  }
  // ========================================================================

  // totals
  const t = totals(items);
  if (elSubtotal) elSubtotal.textContent = fmt(t.subtotal);
  if (elShipping) elShipping.textContent = t.shipping === 0 ? 'FREE' : fmt(t.shipping);
  if (elTax) elTax.textContent = fmt(t.tax);
  if (elTotal) elTotal.textContent = fmt(t.total);

  if (elFSN){
    const need = 150 - t.subtotal;
    if (need > 0){
      elFSN.style.display = '';
      elFSN.textContent = `Add ${fmt(need)} more for free shipping!`;
    } else {
      elFSN.style.display = 'none';
    }
  }

  // event delegation for +/−/remove
  if (elList){
    elList.onclick = (e)=>{
      const btn = e.target.closest('button');
      if (!btn) return;

      const id = Number(btn.dataset.id);
      const action = btn.dataset.action;

      if (action === 'remove') removeItem(id);

      if (action === 'inc'){
        const current = getCart().find(x=>x.id===id)?.qty || 1;
        updateQty(id, current + 1);
      }

      if (action === 'dec'){
        const current = getCart().find(x=>x.id===id)?.qty || 1;
        updateQty(id, current - 1);
      }
    };
  }
}

// ✅ ONLY ONE checkout setup (no cloning, no multiple fallbacks)
function setupCheckout(){
  const btn = document.getElementById('checkoutBtn');
  console.log('Checkout button element:', btn);

  if (!btn) return;

  // If button is inside a <form>, this prevents form submit to old checkout.html
  btn.setAttribute('type', 'button');

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    console.log('Checkout button clicked → checkout.php');
    window.location.assign('checkout.php');
  });
}

// ---- boot ----
document.addEventListener('DOMContentLoaded', () => {
  console.log('DOM fully loaded');
  render();
  setupCheckout();
});