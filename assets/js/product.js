// product.js - handles product page functionality, including filtering, search, and cart management


document.addEventListener("DOMContentLoaded", () => {
    console.log('Products page loaded - initializing filters');
    
    // Initialize category filtering
    initializeCategoryFilters();
    
    // Initialize cart count
    updateCartCount();
    
    // to show all products
    filterProductsByCategory('all');
});

// Initialize category filtering
function initializeCategoryFilters() {
    const categoryButtons = document.querySelectorAll(".category-btn");
    console.log('Found category buttons:', categoryButtons.length);

    categoryButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            console.log('Category button clicked:', button.textContent.trim());
            
        
            document.querySelectorAll(".category-btn").forEach(btn => {
                btn.classList.remove("active");
            });
            button.classList.add("active");

            const selectedCategory = button.dataset.category;
            console.log('Selected category:', selectedCategory);
            
            filterProductsByCategory(selectedCategory);
        });
    });
}

// Filter products by category
function filterProductsByCategory(category) {
    const productCards = document.querySelectorAll(".product-card");
    let visibleCount = 0;

    console.log(`Filtering ${productCards.length} products for category: ${category}`);

    productCards.forEach((card, index) => {
        const cardCategory = card.dataset.category;
        const shouldShow = category === "all" || category === cardCategory;

        console.log(`Product ${index + 1}: category="${cardCategory}", show=${shouldShow}`);

        if (shouldShow) {
            card.style.display = "block";
            card.style.opacity = "1";
            visibleCount++;
            
        
            card.classList.add("fade-in");
        } else {
            card.style.display = "none";
            card.classList.remove("fade-in");
        }
    });
    
    console.log(`Filtering complete: ${visibleCount} products visible`);
    
    // Show message if no products found
    if (visibleCount === 0) {
        showNoProductsMessage(category);
    } else {
        hideNoProductsMessage();
    }
}

// display no products message
function showNoProductsMessage(category) {
    let messageEl = document.getElementById('no-products-message');
    if (!messageEl) {
        messageEl = document.createElement('div');
        messageEl.id = 'no-products-message';
        messageEl.style.textAlign = 'center';
        messageEl.style.padding = '60px 20px';
        messageEl.style.color = 'var(--text-light)';
        messageEl.style.fontSize = '18px';
        
        const productsSection = document.querySelector('.products-section .container');
        productsSection.appendChild(messageEl);
    }
    
    const categoryName = getCategoryDisplayName(category);
    messageEl.innerHTML = `<p>No products found in <strong>${categoryName}</strong> category.</p>
                          <p style="margin-top: 10px; font-size: 14px;">Please check back later or browse other categories.</p>`;
}

function hideNoProductsMessage() {
    const messageEl = document.getElementById('no-products-message');
    if (messageEl) {
        messageEl.remove();
    }
}

function getCategoryDisplayName(categoryKey) {
    const categoryMap = {
        'all': 'All Products',
        'perfume': 'Perfumes',
        'car-perfume': 'Car Perfumes',
        'candle': 'Candles',
        'home-spray': 'Home Sprays',
        'body-wash': 'Body Wash'
    };
    
    return categoryMap[categoryKey] || categoryKey;
}

// ADD TO CART FUNCTIONALITY

function addToCart(productId) {
    let product;
    if (window.productsData) {
        product = window.productsData.find(p => p.id == productId);
    }
    
    if (!product) {
        const productCard = document.querySelector(`[data-id="${productId}"]`)?.closest('.product-card');
        if (productCard) {
            const name = productCard.querySelector('h3')?.textContent || 'Product';
            const priceText = productCard.querySelector('.product-price')?.textContent || '£0.00';
            const price = parseFloat(priceText.replace(/[^\d.]/g, ''));
            const image = productCard.querySelector('img')?.src || '';
            const category = productCard.dataset.category || 'general';
            
            product = { id: productId, name, price, image, category };
        }
    }

    if (!product) {
        console.error("Product not found:", productId);
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existingItem = cart.find(item => item.id == productId);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            category: product.category,
            quantity: 1
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    
    // Show success message
    showAddToCartMessage(product.name);
    updateCartCount();
    
    // Add visual feedback to button
    const button = document.querySelector(`.add-to-cart[data-id="${productId}"]`);
    if (button) {
        const originalText = button.textContent;
        button.textContent = '✓ Added!';
        button.style.backgroundColor = '#4e4138';
        button.style.color = '#ffffff';
        
        setTimeout(() => {
            button.textContent = originalText;
            button.style.backgroundColor = '';
            button.style.color = '';
        }, 1500);
    }
}

// Show add to cart success message
function showAddToCartMessage(productName) {
    const existingToast = document.querySelector('.cart-toast');
    if (existingToast) {
        existingToast.remove();
    }
    const toast = document.createElement('div');
    toast.className = 'cart-toast';
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
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    const counter = document.querySelector(".cart-count");
    if (counter) {
        counter.textContent = count;
        counter.style.display = count > 0 ? 'flex' : 'none';
    }
}

// adding cart button

function initializeAddToCartButtons() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart')) {
            const productId = e.target.dataset.id;
            if (productId) {
                addToCart(productId);
            }
        }
    });
}

initializeAddToCartButtons();

// search functionality

function initializeSearch() {
    const searchInput = document.getElementById('navSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            if (searchTerm.length > 2) {
                filterProductsBySearch(searchTerm);
            } else if (searchTerm.length === 0) {
                const activeCategory = document.querySelector('.category-btn.active');
                if (activeCategory) {
                    filterProductsByCategory(activeCategory.dataset.category);
                } else {
                    filterProductsByCategory('all');
                }
            }
        });
    }
}

function filterProductsBySearch(searchTerm) {
    const productCards = document.querySelectorAll(".product-card");
    let visibleCount = 0;

    productCards.forEach((card) => {
        const productName = card.querySelector('h3').textContent.toLowerCase();
        const productDescription = card.querySelector('.product-description').textContent.toLowerCase();
        
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

    // Show message if no products found
    if (visibleCount === 0) {
        showNoSearchResultsMessage(searchTerm);
    } else {
        hideNoProductsMessage();
    }
}

function showNoSearchResultsMessage(searchTerm) {
    let messageEl = document.getElementById('no-products-message');
    if (!messageEl) {
        messageEl = document.createElement('div');
        messageEl.id = 'no-products-message';
        messageEl.style.textAlign = 'center';
        messageEl.style.padding = '60px 20px';
        messageEl.style.color = 'var(--text-light)';
        messageEl.style.fontSize = '18px';
        
        const productsSection = document.querySelector('.products-section .container');
        productsSection.appendChild(messageEl);
    }
    
    messageEl.innerHTML = `<p>No products found for "<strong>${searchTerm}</strong>".</p>
                          <p style="margin-top: 10px; font-size: 14px;">Try different keywords or browse our categories.</p>`;
}

document.addEventListener('DOMContentLoaded', initializeSearch);

// URL parameters handling

function handleURLParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    const searchParam = urlParams.get('search');
    
    if (categoryParam) {
        const categoryButton = document.querySelector(`[data-category="${categoryParam}"]`);
        if (categoryButton) {
            categoryButton.click();
        }
    }
    
    if (searchParam) {
        const searchInput = document.getElementById('navSearchInput');
        if (searchInput) {
            searchInput.value = searchParam;
            filterProductsBySearch(searchParam.toLowerCase());
        }
    }
}

document.addEventListener('DOMContentLoaded', handleURLParameters);

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
    
    /* Loading state for products */
    .product-card.loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    /* Responsive improvements */
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
function initializeSearch() {
    const searchInput = document.getElementById('navSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            if (searchTerm.length > 2) {
                debouncedSearch(searchTerm);
            } else if (searchTerm.length === 0) {
                const activeCategory = document.querySelector('.category-btn.active');
                if (activeCategory) {
                    filterProductsByCategory(activeCategory.dataset.category);
                } else {
                    filterProductsByCategory('all');
                }
            }
        });
    }
}

// Debug helper
console.log('Product.js loaded successfully - all features initialized');