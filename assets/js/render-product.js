// assets/js/render-products.js
document.addEventListener("DOMContentLoaded", () => {
  const list = document.getElementById("products-list");
  if (!list) return;

  // --- state from URL (optional) ---
  const params = new URLSearchParams(location.search);
  let activeCategory = params.get("category") || "all";
  const searchTerm = (params.get("search") || "").toLowerCase().trim();

  // --- LS helpers (same shape as cart.js) ---
  const LS = {
    get(k, fb){ try { return JSON.parse(localStorage.getItem(k)) ?? fb; } catch { return fb; } },
    set(k, v){ localStorage.setItem(k, JSON.stringify(v)); }
  };
  const CART_KEY = "cart";
  const getCart = () => LS.get(CART_KEY, []);          // [{id, qty}]
  const setCart = (c) => LS.set(CART_KEY, c);
  const addToCart = (id, qty=1) => {
    const cart = getCart();
    const i = cart.findIndex(x => x.id === id);
    if (i > -1) cart[i].qty += qty;
    else cart.push({ id, qty });
    setCart(cart);
  };

  // --- filter + render ---
  function filteredProducts(){
    return (window.productsData || []).filter(p => {
      const catOK = (activeCategory === "all") || (p.category === activeCategory);
      const textOK = !searchTerm ||
        p.name.toLowerCase().includes(searchTerm) ||
        (p.description || "").toLowerCase().includes(searchTerm);
      return catOK && textOK;
    });
  }

  function render(){
    const items = filteredProducts();
    if (!items.length){
      list.innerHTML = `<p style="opacity:.7;">No products found.</p>`;
      return;
    }
    list.innerHTML = items.map(p => `
      <div class="product-card" data-category="${p.category}">
        <div class="product-image">
          <img src="${p.image}" alt="${p.name}">
          <div class="product-overlay">
            <a href="productdetails.html?id=${p.id}" class="hero-btn">View Details</a>
          </div>
        </div>
        <div class="product-info">
          <h3>${p.name}</h3>
          <p class="product-description">${p.description}</p>
          <p class="product-price">£${p.price.toFixed(2)}</p>
          <button class="hero-btn add-to-cart" data-id="${p.id}">Add to Cart</button>
        </div>
      </div>
    `).join("");

    // add-to-cart buttons
    list.querySelectorAll(".add-to-cart").forEach(btn=>{
      btn.addEventListener("click", () => {
        const id = Number(btn.dataset.id);
        addToCart(id, 1);
        alert("Added to cart"); // swap for your toast/snackbar if you have one
      });
    });
  }

  // --- category buttons (use your existing buttons) ---
  const catButtons = document.querySelectorAll(".category-btn");
  catButtons.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      catButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      activeCategory = btn.dataset.category || "all";
      render();
    });
    // set initial active state from URL
    if ((btn.dataset.category || "all") === activeCategory){
      btn.classList.add("active");
    }
  });

  // initial render
  render();
});
