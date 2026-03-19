// assets/js/cart.js
// Renders cart from localStorage + productsData

const LS = {
  get(k, fb) {
    try {
      return JSON.parse(localStorage.getItem(k)) ?? fb;
    } catch {
      return fb;
    }
  },
  set(k, v) {
    localStorage.setItem(k, JSON.stringify(v));
  }
};

const CART_KEY = 'cart';
const FREE_LIMIT = 40;
const STANDARD_SHIPPING = 4.99;

// ---- cart helpers ----
function getCart() {
  const raw = LS.get(CART_KEY, []);
  return raw.map(item => ({
    id: Number(item.id),
    qty: Number(item.qty ?? item.quantity ?? 1)
  }));
}

function setCart(cart) {
  LS.set(CART_KEY, cart);
}

// ---- merge cart with products ----
function mergeCart() {
  const cart = getCart();
  const products = Array.isArray(window.productsData) ? window.productsData : [];

  return cart.map(ci => {
    const p = products.find(x => Number(x.id) === Number(ci.id));

    if (!p) {
      console.warn(`Product not found for cart item id ${ci.id}`);
      return null;
    }

    return {
      ...p,
      name: p.name ?? 'Unnamed Product',
      image: p.image ?? 'assets/images/logo.png',
      price: Number(p.price) || 0,
      qty: Number(ci.qty) || 1
    };
  }).filter(Boolean);
}

// ---- money helpers ----
const fmt = (n) => `£${(Math.round((Number(n) || 0) * 100) / 100).toFixed(2)}`;

function totals(items) {
  const subtotal = items.reduce((sum, item) => {
    const price = Number(item.price) || 0;
    const qty = Number(item.qty) || 1;
    return sum + (price * qty);
  }, 0);

  const shipping = subtotal >= FREE_LIMIT ? 0 : (items.length ? STANDARD_SHIPPING : 0);
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
const elFSN = document.getElementById('fsn');

// ---- actions ----
function updateQty(id, nextQty) {
  const qty = Math.max(1, Math.min(10, Number(nextQty) || 1));
  const cart = getCart().map(item => item.id === id ? { ...item, qty } : item);
  setCart(cart);
  render();
}

function removeItem(id) {
  const cart = getCart().filter(item => item.id !== id);
  setCart(cart);
  render();
}

// ---- rendering ----
function render() {
  const items = mergeCart();

  if (elCount) {
    const totalQty = items.reduce((sum, item) => sum + item.qty, 0);
    elCount.textContent = totalQty;
  }

  if (!items.length) {
    if (elList) {
      elList.innerHTML = `
        <div class="empty-cart">
          <p>Your cart is empty</p>
          <button
            class="btn-primary"
            type="button"
            style="max-width:300px;margin:0 auto;"
            onclick="location.href='products.php'">
            Continue Shopping
          </button>
        </div>
      `;
    }

    if (elSubtotal) elSubtotal.textContent = fmt(0);
    if (elShipping) elShipping.textContent = fmt(0);
    if (elTax) elTax.textContent = fmt(0);
    if (elTotal) elTotal.textContent = fmt(0);
    if (elFSN) elFSN.style.display = 'none';
    return;
  }

  if (elList) {
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

  const t = totals(items);

  if (elSubtotal) elSubtotal.textContent = fmt(t.subtotal);
  if (elShipping) elShipping.textContent = t.shipping === 0 ? 'FREE' : fmt(t.shipping);
  if (elTax) elTax.textContent = fmt(t.tax);
  if (elTotal) elTotal.textContent = fmt(t.total);

  if (elFSN) {
    const need = FREE_LIMIT - t.subtotal;

    if (need > 0) {
      elFSN.style.display = '';
      elFSN.textContent = `Add ${fmt(need)} more for free shipping!`;
    } else {
      elFSN.style.display = 'none';
    }
  }
}

// ---- events ----
if (elList) {
  elList.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;

    const id = Number(btn.dataset.id);
    const action = btn.dataset.action;

    if (action === 'remove') {
      removeItem(id);
      return;
    }

    if (action === 'inc') {
      const current = getCart().find(x => x.id === id)?.qty || 1;
      updateQty(id, current + 1);
      return;
    }

    if (action === 'dec') {
      const current = getCart().find(x => x.id === id)?.qty || 1;
      updateQty(id, current - 1);
    }
  });
}

function setupCheckout() {
  const btn = document.getElementById('checkoutBtn');
  if (!btn) return;

  btn.setAttribute('type', 'button');

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    window.location.assign('checkout.php');
  });
}

// ---- boot ----
document.addEventListener('DOMContentLoaded', () => {
  render();
  setupCheckout();
});