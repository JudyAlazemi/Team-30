// assets/js/wire-products-lite.js
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".product-card[data-id]");
    const data = window.productsData || [];

    cards.forEach(card => {
        const id = Number(card.dataset.id);
        const p = data.find(x => x.id === id);
        if (!p) return;

        // 1) update image
        const img = card.querySelector(".product-image img");
        if (img && p.image) {
            img.src = p.image;
            img.alt = p.name;
        }

        // 2) update name
        const title = card.querySelector(".product-info h3");
        if (title) title.textContent = p.name;

        // 3) update price
        const price = card.querySelector(".product-price");
        if (price) price.textContent = `£${p.price.toFixed(2)}`;

        // 4) update view details link
        const link = card.querySelector(".product-overlay a");
        if (link) link.href = `productdetails.html?id=${p.id}`;

        // 5) ensure add-to-cart button uses correct ID
        const addBtn = card.querySelector(".add-to-cart");
        if (addBtn) addBtn.dataset.id = p.id;

        // 6) OPTIONAL: make whole card clickable except buttons
        card.addEventListener("click", (e) => {
            if (e.target.closest("button") || e.target.closest("a")) return;
            window.location.href = `productdetails.html?id=${p.id}`;
        });
    });
});
