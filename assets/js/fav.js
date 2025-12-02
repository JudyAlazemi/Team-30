document.addEventListener('DOMContentLoaded', () => {

    // Apply favourite state to all hearts
    function applyFavourites(favList) {
        const favSet = new Set(favList.map(String));

        document.querySelectorAll('.fav-toggle[data-product-id]').forEach(btn => {
            const id = btn.dataset.productId;

            if (favSet.has(id)) {
                btn.classList.add('is-fav');
                btn.setAttribute('aria-pressed', 'true');
            } else {
                btn.classList.remove('is-fav');
                btn.setAttribute('aria-pressed', 'false');
            }
        });
    }

    // Load favourites for current user/session
    function loadFavourites() {
        fetch('favourites.php', {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ action: "list" })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                applyFavourites(data.favourites);
            }
        })
        .catch(err => console.error("Fav load error:", err));
    }

    // Toggle favourite when clicking heart
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('.fav-toggle');
        if (!btn) return;

        const productId = btn.dataset.productId;

        fetch('favourites.php', {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                action: "toggle",
                product_id: productId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                // Update UI instantly
                if (data.favourited) {
                    btn.classList.add('is-fav');
                    btn.setAttribute('aria-pressed', 'true');
                } else {
                    btn.classList.remove('is-fav');
                    btn.setAttribute('aria-pressed', 'false');
                }
            }
        })
        .catch(err => console.error("Fav toggle error:", err));
    });

    loadFavourites();
});
