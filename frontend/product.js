// Products Page JavaScript

// Sample product data
const products = [
    {
        id: 1,
        name: "Midnight Oud",
        description: "Deep and mysterious oriental fragrance",
        price: 89.99,
        image: "images/products/perfume1.jpg"
    },
    {
        id: 2,
        name: "Rose Garden",
        description: "Elegant floral bouquet with rose essence",
        price: 79.99,
        image: "images/products/perfume2.jpg"
    },
    {
        id: 3,
        name: "Citrus Breeze",
        description: "Fresh and energizing citrus notes",
        price: 69.99,
        image: "images/products/perfume3.jpg"
    },
    {
        id: 4,
        name: "Amber Nights",
        description: "Warm amber with vanilla undertones",
        price: 94.99,
        image: "images/products/perfume4.jpg"
    },
    {
        id: 5,
        name: "Ocean Mist",
        description: "Aquatic and refreshing marine scent",
        price: 74.99,
        image: "images/products/perfume5.jpg"
    },
    {
        id: 6,
        name: "Velvet Musk",
        description: "Sensual musk with woody accents",
        price: 84.99,
        image: "images/products/perfume6.jpg"
    },
    {
        id: 7,
        name: "Jasmine Dreams",
        description: "Exotic jasmine with hints of sandalwood",
        price: 79.99,
        image: "images/products/perfume7.jpg"
    },
    {
        id: 8,
        name: "Spice Route",
        description: "Bold oriental spices with leather base",
        price: 99.99,
        image: "images/products/perfume8.jpg"
    }
];

// Add to cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            addToCart(productId);
        });
    });
});

function addToCart(productId) {
    // Get existing cart from localStorage or create new one
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Find the product
    const product = products.find(p => p.id == productId);
    
    if (product) {
        // Check if product already exists in cart
        const existingItem = cart.find(item => item.id == productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: 1
            });
        }
        
        // Save cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Show success message
        alert(`${product.name} has been added to your cart!`);
        
        // Update cart count if you have a cart counter in your navbar
        updateCartCount();
    }
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Update cart counter in navbar if it exists
    const cartCounter = document.querySelector('.cart-count');
    if (cartCounter) {
        cartCounter.textContent = totalItems;
    }
}

// Initialize cart count on page load
updateCartCount();