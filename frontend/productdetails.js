// Product Details Page JavaScript

// Sample product data (same as products.js)
const products = [
    {
        id: 1,
        name: "Midnight Oud",
        description: "Midnight Oud is a luxurious and captivating fragrance that embodies elegance and mystery. This deep oriental scent features rich oud wood as its heart, complemented by warm spices and a hint of amber. Perfect for evening wear and special occasions.",
        price: 89.99,
        image: "images/products/perfume1.jpg",
        images: ["images/products/perfume1.jpg", "images/products/perfume1-alt1.jpg", "images/products/perfume1-alt2.jpg"],
        notes: {
            top: "Bergamot, Saffron, Pink Pepper",
            heart: "Oud Wood, Rose, Patchouli",
            base: "Amber, Musk, Sandalwood"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Unisex",
            longevity: "8-10 hours",
            sillage: "Strong"
        }
    },
    {
        id: 2,
        name: "Rose Garden",
        description: "Rose Garden captures the essence of a blooming rose garden in full spring. This elegant floral fragrance combines the delicate sweetness of fresh roses with subtle green notes and a touch of powder. A timeless and romantic scent perfect for any occasion.",
        price: 79.99,
        image: "images/products/perfume2.jpg",
        images: ["images/products/perfume2.jpg", "images/products/perfume2-alt1.jpg", "images/products/perfume2-alt2.jpg"],
        notes: {
            top: "Mandarin, Green Leaves, Lemon",
            heart: "Rose, Peony, Jasmine",
            base: "White Musk, Cedar, Powder"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Women",
            longevity: "6-8 hours",
            sillage: "Moderate"
        }
    },
    {
        id: 3,
        name: "Citrus Breeze",
        description: "Citrus Breeze is a vibrant and refreshing fragrance that captures the energy of a sunny morning. Bursting with zesty citrus notes and hints of fresh herbs, this scent is perfect for daily wear and keeps you feeling energized throughout the day.",
        price: 69.99,
        image: "images/products/perfume3.jpg",
        images: ["images/products/perfume3.jpg", "images/products/perfume3-alt1.jpg", "images/products/perfume3-alt2.jpg"],
        notes: {
            top: "Lemon, Orange, Grapefruit",
            heart: "Basil, Mint, Neroli",
            base: "Vetiver, Cedar, Light Musk"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Toilette",
            gender: "Unisex",
            longevity: "4-6 hours",
            sillage: "Light"
        }
    },
    {
        id: 4,
        name: "Amber Nights",
        description: "Amber Nights is a warm and sensual fragrance that evokes cozy evenings by the fireplace. Rich amber blends with creamy vanilla and exotic spices to create a comforting and luxurious scent that lingers beautifully on the skin.",
        price: 94.99,
        image: "images/products/perfume4.jpg",
        images: ["images/products/perfume4.jpg", "images/products/perfume4-alt1.jpg", "images/products/perfume4-alt2.jpg"],
        notes: {
            top: "Cinnamon, Cardamom, Bergamot",
            heart: "Amber, Honey, Labdanum",
            base: "Vanilla, Tonka Bean, Benzoin"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Unisex",
            longevity: "8-12 hours",
            sillage: "Strong"
        }
    },
    {
        id: 5,
        name: "Ocean Mist",
        description: "Ocean Mist brings the freshness of the sea to your everyday life. This aquatic fragrance combines crisp marine notes with subtle florals and woody undertones, creating a clean and invigorating scent perfect for summer days.",
        price: 74.99,
        image: "images/products/perfume5.jpg",
        images: ["images/products/perfume5.jpg", "images/products/perfume5-alt1.jpg", "images/products/perfume5-alt2.jpg"],
        notes: {
            top: "Sea Salt, Bergamot, Mint",
            heart: "Marine Accord, Lavender, Sage",
            base: "Driftwood, Ambergris, Musk"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Toilette",
            gender: "Men",
            longevity: "5-7 hours",
            sillage: "Moderate"
        }
    },
    {
        id: 6,
        name: "Velvet Musk",
        description: "Velvet Musk is a sophisticated and sensual fragrance that combines soft musk with warm woody notes. This elegant scent is perfect for those who appreciate understated luxury and want a signature fragrance that's both intimate and memorable.",
        price: 84.99,
        image: "images/products/perfume6.jpg",
        images: ["images/products/perfume6.jpg", "images/products/perfume6-alt1.jpg", "images/products/perfume6-alt2.jpg"],
        notes: {
            top: "Pink Pepper, Iris, Bergamot",
            heart: "White Musk, Violet, Rose",
            base: "Sandalwood, Cashmere Wood, Amber"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Unisex",
            longevity: "7-9 hours",
            sillage: "Moderate to Strong"
        }
    },
    {
        id: 7,
        name: "Jasmine Dreams",
        description: "Jasmine Dreams is an exotic and enchanting fragrance that captures the intoxicating beauty of night-blooming jasmine. Blended with creamy sandalwood and subtle spices, this scent is both feminine and mysterious, perfect for evening wear.",
        price: 79.99,
        image: "images/products/perfume7.jpg",
        images: ["images/products/perfume7.jpg", "images/products/perfume7-alt1.jpg", "images/products/perfume7-alt2.jpg"],
        notes: {
            top: "Mandarin, Cardamom, Peach",
            heart: "Jasmine Sambac, Ylang-Ylang, Orange Blossom",
            base: "Sandalwood, Vanilla, White Musk"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Women",
            longevity: "6-8 hours",
            sillage: "Moderate"
        }
    },
    {
        id: 8,
        name: "Spice Route",
        description: "Spice Route takes you on an olfactory journey through ancient spice markets. This bold and exotic fragrance combines rich oriental spices with leather and smoky incense, creating a powerful and unforgettable scent for the confident individual.",
        price: 99.99,
        image: "images/products/perfume8.jpg",
        images: ["images/products/perfume8.jpg", "images/products/perfume8-alt1.jpg", "images/products/perfume8-alt2.jpg"],
        notes: {
            top: "Black Pepper, Nutmeg, Cinnamon",
            heart: "Clove, Tobacco, Leather",
            base: "Oud, Incense, Patchouli"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Men",
            longevity: "10-12 hours",
            sillage: "Very Strong"
        }
    }
];

// Get product ID from URL parameter
function getProductIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id') || '1';
}

// Load product details on page load
document.addEventListener('DOMContentLoaded', function() {
    const productId = getProductIdFromURL();
    loadProductDetails(productId);
});

function loadProductDetails(productId) {
    const product = products.find(p => p.id == productId);
    
    if (product) {
        // Update page title
        document.title = `${product.name} - SABIL Perfumes`;
        
        // Update main product details
        document.getElementById('productName').textContent = product.name;
        document.getElementById('productPrice').textContent = `$${product.price.toFixed(2)}`;
        document.getElementById('productDescription').textContent = product.description;
        
        // Update main image
        document.getElementById('mainProductImage').src = product.image;
        document.getElementById('mainProductImage').alt = product.name;
        
        // Store current product ID for cart functions
        window.currentProductId = productId;
    }
}

// Change main image when clicking thumbnails
function changeImage(imageSrc) {
    document.getElementById('mainProductImage').src = imageSrc;
    
    // Update active thumbnail
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        if (thumb.src.includes(imageSrc)) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
}

// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Add to cart functionality
function addToCart() {
    const productId = window.currentProductId;
    const quantity = parseInt(document.getElementById('quantity').value);
    
    // Get existing cart from localStorage or create new one
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Find the product
    const product = products.find(p => p.id == productId);
    
    if (product) {
        // Check if product already exists in cart
        const existingItem = cart.find(item => item.id == productId);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: quantity
            });
        }
        
        // Save cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Show success message
        alert(`${quantity} x ${product.name} has been added to your cart!`);
        
        // Reset quantity to 1
        document.getElementById('quantity').value = 1;
    }
}

// Buy now functionality
function buyNow() {
    addToCart();
    // Redirect to cart page
    window.location.href = 'cart.html';
}