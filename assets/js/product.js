// product.js - handles product page functionality, including filtering, search, and cart management

document.addEventListener("DOMContentLoaded", () => {
    initializeCategoryFilters();
    initializeAddToCartButtons();
    initializeSearch();
    handleURLParameters();
    updateCartCount();
    filterProductsByCategory("all");
});

// CATEGORY FILTER
function initializeCategoryFilters() {
    const categoryButtons = document.querySelectorAll(".category-btn");

    categoryButtons.forEach(button => {
        button.addEventListener("click", () => {
            categoryButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            const selectedCategory = button.dataset.category;
            filterProductsByCategory(selectedCategory);
        });
    });
}

function filterProductsByCategory(category) {
    const productCards = document.querySelectorAll(".product-card");
    let visibleCount = 0;

    productCards.forEach(card => {
        const cardCategory = card.dataset.category;
        const shouldShow = category === "all" || category === cardCategory;

        if (shouldShow) {
            card.style.display = "flex";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });

    if (visibleCount === 0) {
        showNoProductsMessage(category);
    } else {
        hideNoProductsMessage();
    }
}

function showNoProductsMessage(category) {
    let messageEl = document.getElementById("no-products-message");

    if (!messageEl) {
        messageEl = document.createElement("div");
        messageEl.id = "no-products-message";
        messageEl.style.textAlign = "center";
        messageEl.style.padding = "40px 20px";
        messageEl.style.color = "var(--text-light)";
        messageEl.style.fontSize = "18px";

        const productsSection = document.querySelector(".products-section .container");
        if (productsSection) {
            productsSection.appendChild(messageEl);
        }
    }

    const categoryName = getCategoryDisplayName(category);
    messageEl.innerHTML = `<p>No products found in <strong>${categoryName}</strong>.</p>`;
}

function hideNoProductsMessage() {
    const messageEl = document.getElementById("no-products-message");
    if (messageEl) {
        messageEl.remove();
    }
}

function getCategoryDisplayName(categoryKey) {
    const categoryMap = {
        all: "All Products",
        perfume: "Perfumes",
        "car-perfume": "Car Perfumes",
        candle: "Candles",
        "home-spray": "Home Sprays",
        "body-wash": "Body Wash"
    };

    return categoryMap[categoryKey] || categoryKey;
}

// ADD TO CART FUNCTIONALITY
function addToCart(productId) {
    let product = null;

    if (window.productsData) {
        product = window.productsData.find(p => Number(p.id) === Number(productId));
    }

    if (!product) {
        const productCard = document.querySelector(`.product-card[data-id="${productId}"]`);
        if (productCard) {
            const name = productCard.querySelector("h3")?.textContent?.trim() || "Product";
            const priceText = productCard.querySelector(".product-price")?.textContent || "£0.00";
            const price = parseFloat(priceText.replace(/[^\d.]/g, "")) || 0;
            const image = productCard.querySelector("img")?.getAttribute("src") || "";
            const category = productCard.dataset.category || "general";

            product = {
                id: Number(productId),
                name,
                price,
                image,
                category
            };
        }
    }

    if (!product) {
        console.error("Product not found:", productId);
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existingItem = cart.find(item => Number(item.id) === Number(product.id));

    if (existingItem) {
        existingItem.quantity = Number(existingItem.quantity || 1) + 1;
    } else {
        cart.push({
            id: Number(product.id),
            name: product.name,
            price: Number(product.price),
            image: product.image,
            category: product.category,
            quantity: 1
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    showAddToCartMessage(product.name);
    updateCartCount();

    const button = document.querySelector(`.add-to-cart[data-id="${product.id}"]`);
    if (button) {
        const originalText = button.textContent;
        button.textContent = "✓ Added!";
        button.style.backgroundColor = "#4e4138";
        button.style.color = "#ffffff";

        setTimeout(() => {
            button.textContent = originalText;
            button.style.backgroundColor = "";
            button.style.color = "";
        }, 1500);
    }
}

// Show add to cart success message
function showAddToCartMessage(productName) {
    const existingToast = document.querySelector(".cart-toast");
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement("div");
    toast.className = "cart-toast";
    toast.innerHTML = `<span>✓ ${productName} added to cart!</span>`;
    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

// cart management
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const count = cart.reduce((total, item) => total + Number(item.quantity || 1), 0);
    const counter = document.querySelector(".cart-count");

    if (counter) {
        counter.textContent = count;
        counter.style.display = count > 0 ? "flex" : "none";
    }
}

// add to cart button binding
function initializeAddToCartButtons() {
    document.addEventListener("click", function (e) {
        const button = e.target.closest(".add-to-cart");
        if (!button) return;

        e.preventDefault();
        e.stopPropagation();

        const productId = button.dataset.id;
        if (productId) {
            addToCart(productId);
        }
    });
}

// search functionality
function initializeSearch() {
    const searchInput = document.getElementById("navSearchInput");
    if (searchInput) {
        searchInput.addEventListener("input", function (e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            if (searchTerm.length > 2) {
                debouncedSearch(searchTerm);
            } else if (searchTerm.length === 0) {
                const activeCategory = document.querySelector(".category-btn.active");
                if (activeCategory) {
                    filterProductsByCategory(activeCategory.dataset.category);
                } else {
                    filterProductsByCategory("all");
                }
            }
        });
    }
}

function filterProductsBySearch(searchTerm) {
    const productCards = document.querySelectorAll(".product-card");
    let visibleCount = 0;

    productCards.forEach((card) => {
        const productName = card.querySelector("h3")?.textContent.toLowerCase() || "";
        const productDescription = card.querySelector(".product-description")?.textContent.toLowerCase() || "";

        const matchesSearch = productName.includes(searchTerm) || productDescription.includes(searchTerm);

        if (matchesSearch) {
            card.style.display = "block";
            card.style.opacity = "1";
            visibleCount++;
            card.classList.add("fade-in");
        } else {
            card.style.display = "none";
            card.classList.remove("fade-in");
        }
    });

    if (visibleCount === 0) {
        showNoSearchResultsMessage(searchTerm);
    } else {
        hideNoProductsMessage();
    }
}

function showNoSearchResultsMessage(searchTerm) {
    let messageEl = document.getElementById("no-products-message");
    if (!messageEl) {
        messageEl = document.createElement("div");
        messageEl.id = "no-products-message";
        messageEl.style.textAlign = "center";
        messageEl.style.padding = "60px 20px";
        messageEl.style.color = "var(--text-light)";
        messageEl.style.fontSize = "18px";

        const productsSection = document.querySelector(".products-section .container");
        if (productsSection) {
            productsSection.appendChild(messageEl);
        }
    }

    messageEl.innerHTML = `<p>No products found for "<strong>${searchTerm}</strong>".</p>
                          <p style="margin-top: 10px; font-size: 14px;">Try different keywords or browse our categories.</p>`;
}

// URL parameters handling
function handleURLParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get("category");
    const searchParam = urlParams.get("search");

    if (categoryParam) {
        const categoryButton = document.querySelector(`[data-category="${categoryParam}"]`);
        if (categoryButton) {
            categoryButton.click();
        }
    }

    if (searchParam) {
        const searchInput = document.getElementById("navSearchInput");
        if (searchInput) {
            searchInput.value = searchParam;
            filterProductsBySearch(searchParam.toLowerCase());
        }
    }
}

// css animations and styles
const style = document.createElement("style");
style.textContent = `
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .cart-count {
        background: #4e4138;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        display: none;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: -5px;
        right: -5px;
        font-family: Arial, sans-serif;
    }

    .cart-toast {
        position: fixed;
        top: 120px;
        right: 20px;
        background: #4e4138;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        animation: slideIn 0.3s ease, slideOut 0.3s ease 2.7s forwards;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }

    .category-btn {
        transition: all 0.3s ease;
    }

    .category-btn:hover {
        transform: translateY(-2px);
    }

    .product-card {
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .cart-toast {
            top: 100px;
            right: 10px;
            left: 10px;
        }
    }

    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .category-filters {
            gap: 10px;
        }

        .category-btn {
            padding: 10px 16px;
            font-size: 14px;
        }
    }
`;
document.head.appendChild(style);

// performance optimization - debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

const debouncedSearch = debounce(function(searchTerm) {
    filterProductsBySearch(searchTerm);
}, 300);

console.log("Product.js loaded successfully - add to cart fixed");