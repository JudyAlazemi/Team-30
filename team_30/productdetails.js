// Product Details Page 

const products = [
    // PERFUMES (3 products)
    {
        id: 1,
        name: "Ocean Breeze",
        description: "A clean, refreshing scent recalls ocean waves and fresh coastal air. It's a light, everyday fragrance that will make you feel refreshed.",
        price: 59.99,
        image: "images/oceanmist.png",
        images: ["images/oceanmist.png", "images/oceanmist.png", "images/oceanmist.png"],
        notes: {
            top: "Sea Salt, Bergamot, Marine Notes",
            heart: "Lavender, Sage, Ocean Breeze",
            base: "Amber, Musk, Driftwood"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Toilette",
            gender: "Unisex",
            longevity: "6-8 hours",
            sillage: "Moderate"
        },
        reviews: {
            rating: 4.7,
            count: 156,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 2,
        name: "Midnight Oud",
        description: "A rich, deep, luxurious fragrance with woody oud, warm spices and flowers. Perfect for evening.",
        price: 69.99,
        image: "images/midnightoud.png",
        images: ["images/midnightoud.png", "images/midnightoud.png", "images/midnightoud.png"],
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
        },
        reviews: {
            rating: 4.8,
            count: 203,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 3,
        name: "Velvet Rose",
        description: "A luxurious, classic floral fragrance. Warm and musky, with a hint of rose and gentle spices. A touch of luxury for everyday wear.",
        price: 64.99,
        image: "images/velvetmusk.png",
        images: ["images/velvetmusk.png", "images/velvetmusk.png", "images/velvetmusk.png"],
        notes: {
            top: "Bulgarian Rose, Pink Pepper",
            heart: "Velvet Musk, Peony, Jasmine",
            base: "Sandalwood, Amber, Vanilla"
        },
        specs: {
            size: "100ml / 3.4 fl oz",
            concentration: "Eau de Parfum",
            gender: "Women",
            longevity: "7-9 hours",
            sillage: "Moderate to Strong"
        },
        reviews: {
            rating: 4.6,
            count: 178,
            stars: "⭐⭐⭐⭐⭐"
        }
    },

    // CAR PERFUMES (3 products)
    {
        id: 4,
        name: "Unleaded Petrol",
        description: "For the true automotive enthusiast. A strong, alive car fragrance. Clean, refreshing notes with a metallic freshness. Keeps you awake while driving. Bringing the smell of petrol in your car.",
        price: 16.99,
        image: "images/carperfumedark.png",
        images: ["images/carperfumedark.png", "images/carperfumedark.png", "images/carperfumedark.png"],
        notes: {
            top: "Citrus Zest, Metallic Notes",
            heart: "Clean Air, Green Notes",
            base: "Amber, Musk, Leather"
        },
        specs: {
            size: "50ml / 1.7 fl oz",
            concentration: "Car Diffuser",
            gender: "Unisex",
            longevity: "4-6 weeks",
            sillage: "Strong"
        },
        reviews: {
            rating: 4.4,
            count: 189,
            stars: "⭐⭐⭐⭐☆"
        }
    },
    {
        id: 5,
        name: "Ionix Fresh",
        description: "Purifies your car's air and removes odors. It's a light, everyday fragrance that will make you feel refreshed.",
        price: 14.99,
        image: "images/carperfumelight.png",
        images: ["images/carperfumelight.png", "images/carperfumelight.png", "images/carperfumelight.png"],
        notes: {
            top: "Mountain Air, Ozone",
            heart: "Green Tea, Bamboo",
            base: "Clean Musk, Amber"
        },
        specs: {
            size: "50ml / 1.7 fl oz",
            concentration: "Ionizing Car Diffuser",
            gender: "Unisex",
            longevity: "5-7 weeks",
            sillage: "Moderate"
        },
        reviews: {
            rating: 4.5,
            count: 234,
            stars: "⭐⭐⭐⭐☆"
        }
    },
    {
        id: 6,
        name: "Lavender Cruise",
        description: "A calming car scent with soothing lavender, which is perfectly balanced with calming chamomile, turning traffic into moments of peaceful, focused relaxation.",
        price: 18.99,
        image: "images/carperfume.png",
        images: ["images/carperfume.png", "images/careperfume.png", "images/carperfume.png"],
        notes: {
            top: "Lavender, Bergamot",
            heart: "Chamomile, Herbal Notes",
            base: "Musk, Amber, Vanilla"
        },
        specs: {
            size: "50ml / 1.7 fl oz",
            concentration: "Car Diffuser",
            gender: "Unisex",
            longevity: "4-6 weeks",
            sillage: "Light to Moderate"
        },
        reviews: {
            rating: 4.7,
            count: 167,
            stars: "⭐⭐⭐⭐⭐"
        }
    },

    // CANDLES (3 products)
    {
        id: 7,
        name: "Vanilla Dream Candle",
        description: "A warm, cozy vanilla scent in a soy wax candle. This candle creates an instantly cozy and welcoming atmosphere in any room.",
        price: 12.99,
        image: "images/candle1.png",
        images: ["images/candle1.png", "images/candle1.png", "images/candle1.png"],
        notes: {
            top: "Madagascar Vanilla, Cream",
            heart: "Tonka Bean, Caramel",
            base: "Sandalwood, Musk, Amber"
        },
        specs: {
            size: "300g / 10.5 oz",
            concentration: "Soy Wax Candle",
            gender: "Unisex",
            longevity: "50-60 hours",
            sillage: "Room Filling"
        },
        reviews: {
            rating: 4.9,
            count: 312,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 8,
        name: "Amber Woods Candle",
        description: "A warm, woody scent with amber. Creates a cozy and luxurious atmosphere.",
        price: 16.99,
        image: "images/candle1.png",
        images: ["images/candle1.png", "images/candle1.png", "images/candle1.png"],
        notes: {
            top: "Amber, Cardamom",
            heart: "Sandalwood, Cedarwood",
            base: "Patchouli, Vanilla, Musk"
        },
        specs: {
            size: "300g / 10.5 oz",
            concentration: "Soy Wax Candle",
            gender: "Unisex",
            longevity: "50-60 hours",
            sillage: "Room Filling"
        },
        reviews: {
            rating: 4.8,
            count: 189,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 9,
        name: "Cherry Blossom Candle",
        description: "A soft, floral cherry blossom scent. Creates a fresh and uplifting atmosphere.",
        price: 14.99,
        image: "images/candle1.png",
        images: ["images/candle1.png", "images/candle1.png", "images/candle1.png"],
        notes: {
            top: "Cherry Blossom, Green Notes",
            heart: "Rose, Peony, Lily",
            base: "Musk, Amber, Powder"
        },
        specs: {
            size: "300g / 10.5 oz",
            concentration: "Soy Wax Candle",
            gender: "Women",
            longevity: "50-60 hours",
            sillage: "Moderate to Strong"
        },
        reviews: {
            rating: 4.6,
            count: 245,
            stars: "⭐⭐⭐⭐⭐"
        }
    },

    // HOME SPRAYS (3 products)
    {
        id: 10,
        name: "Lavender Cloud Spray",
        description: "Creates a soothing mist of relaxation throughout your home. This mist of true English lavender and a hint of herbal sage refreshes linens. Promoting rest and tranquility.",
        price: 17.99,
        image: "images/homespraysilver.png",
        images: ["images/homespraysilver.png", "images/homespraysilver.png", "images/homespraysilver.png"],
        notes: {
            top: "Lavender, Bergamot",
            heart: "Chamomile, Herbal Notes",
            base: "Musk, Amber, Vanilla"
        },
        specs: {
            size: "200ml / 6.7 fl oz",
            concentration: "Room Spray",
            gender: "Unisex",
            longevity: "4-6 hours",
            sillage: "Room Filling"
        },
        reviews: {
            rating: 4.7,
            count: 198,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 11,
        name: "Jasmine Home Spray",
        description: "Instantly refreshes any room with its exotic floral fragrance. The charming nighttime bloom of jasmine, lifted by a hint of green tea, creates a luxurious and welcoming floral aura.",
        price: 19.99,
        image: "images/homespraygold.png",
        images: ["images/homespraygold.png", "images/homespraygold.png", "images/homespraygold.png"],
        notes: {
            top: "Jasmine Sambac, Orange Blossom",
            heart: "Ylang-Ylang, Tuberose",
            base: "White Musk, Amber, Sandalwood"
        },
        specs: {
            size: "200ml / 6.7 fl oz",
            concentration: "Room Spray",
            gender: "Unisex",
            longevity: "4-6 hours",
            sillage: "Room Filling"
        },
        reviews: {
            rating: 4.5,
            count: 167,
            stars: "⭐⭐⭐⭐☆"
        }
    },
    {
        id: 12,
        name: "Ocean Breeze Spray",
        description: "A breath of coastal calm. This light, water-kissed mix of ozone, sea salt clears unwanted scents and fills your home with a clean, open, seaside feel.",
        price: 16.99,
        image: "images/homesprayblue.png",
        images: ["images/homesprayblue.png", "images/homesprayblue.png", "images/homesprayblue.png"],
        notes: {
            top: "Sea Salt, Marine Notes",
            heart: "Lavender, Sage, Mint",
            base: "Amber, Musk, Driftwood"
        },
        specs: {
            size: "200ml / 6.7 fl oz",
            concentration: "Room Spray",
            gender: "Unisex",
            longevity: "4-6 hours",
            sillage: "Room Filling"
        },
        reviews: {
            rating: 4.6,
            count: 223,
            stars: "⭐⭐⭐⭐⭐"
        }
    },

    // BODY WASH (3 products)
    {
        id: 13,
        name: "Tropical Breeze Body Wash",
        description: "Transports you to a paradise island with exotic fruit fusion. Leaving your skin refreshed and slightly scented with tropical bliss.",
        price: 8.99,
        image: "images/tropicalbreeze.png",
        images: ["images/tropicalbreeze.png", "images/tropicalbreeze.png", "images/tropicalbreeze.png"],
        notes: {
            top: "Pineapple, Mango, Coconut",
            heart: "Passion Fruit, Citrus",
            base: "Vanilla, Musk, Amber"
        },
        specs: {
            size: "300ml / 10.1 fl oz",
            concentration: "Body Wash",
            gender: "Unisex",
            longevity: "On skin: 2-4 hours",
            sillage: "Light"
        },
        reviews: {
            rating: 4.8,
            count: 276,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 14,
        name: "Strawberry Silk Body Wash",
        description: "A sweet, berry-kissed luxury. Juicy strawberry with a whisper of whipped cream and silky proteins creates a gentle cleanse that leaves your skin supple and sweetly fragrant.",
        price: 9.99,
        image: "images/strawberrysilk.png",
        images: ["images/strawberrysilk.png", "images/strawberrysilk.png", "images/strawberrysilk.png"],
        notes: {
            top: "Strawberry, Raspberry",
            heart: "Cream, Vanilla",
            base: "Musk, Sandalwood"
        },
        specs: {
            size: "300ml / 10.1 fl oz",
            concentration: "Body Wash with Silk Proteins",
            gender: "Women",
            longevity: "On skin: 3-5 hours",
            sillage: "Light to Moderate"
        },
        reviews: {
            rating: 4.7,
            count: 189,
            stars: "⭐⭐⭐⭐⭐"
        }
    },
    {
        id: 15,
        name: "Ultra Fresh Body Wash",
        description: "The ultimate morning wake-up call. A mix of cool mint, lively citrus, and spicy crushed ginger awakens the senses and leaves you feeling fresh, chilled, and energized.",
        price: 7.99,
        image: "images/ultrafresh.png",
        images: ["images/ultrafresh.png", "images/ultrafresh.png", "images/ultrafresh.png"],
        notes: {
            top: "Peppermint, Eucalyptus",
            heart: "Citrus, Green Notes",
            base: "Clean Musk, Amber"
        },
        specs: {
            size: "300ml / 10.1 fl oz",
            concentration: "Body Wash",
            gender: "Unisex",
            longevity: "On skin: 2-4 hours",
            sillage: "Light"
        },
        reviews: {
            rating: 4.4,
            count: 312,
            stars: "⭐⭐⭐⭐☆"
        }
    }
];

function getProductIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id') || '1';
}

document.addEventListener('DOMContentLoaded', function() {
    const productId = getProductIdFromURL();
    console.log('Loading product ID:', productId);
    loadProductDetails(productId);
});

function loadProductDetails(productId) {
    const product = products.find(p => p.id == productId);
    
    console.log('Found product:', product);
    
    if (product) {

        document.title = `${product.name} - SABIL Perfumes`;
        
        //main product 
        document.getElementById('productName').textContent = product.name;
        document.getElementById('productPrice').textContent = `£${product.price.toFixed(2)}`;
        document.getElementById('productDescription').textContent = product.description;
        
        document.getElementById('mainProductImage').src = product.image;
        document.getElementById('mainProductImage').alt = product.name;
        
        if (product.reviews) {
            const reviewsElement = document.querySelector('.product-rating');
            if (reviewsElement) {
                reviewsElement.innerHTML = `${product.reviews.stars} (${product.reviews.count} reviews)`;
            }
        }
        
        // fragrance notes
        document.querySelector('[data-note="top"]').textContent = product.notes.top;
        document.querySelector('[data-note="heart"]').textContent = product.notes.heart;
        document.querySelector('[data-note="base"]').textContent = product.notes.base;
        
        // product specs
        document.querySelector('[data-spec="size"]').textContent = product.specs.size;
        document.querySelector('[data-spec="concentration"]').textContent = product.specs.concentration;
        document.querySelector('[data-spec="gender"]').textContent = product.specs.gender;
        document.querySelector('[data-spec="longevity"]').textContent = product.specs.longevity;
        document.querySelector('[data-spec="sillage"]').textContent = product.specs.sillage;
        
        const thumbnails = document.querySelectorAll('.thumbnail');
        if (product.images && product.images.length > 0) {
            product.images.forEach((imgSrc, index) => {
                if (thumbnails[index]) {
                    thumbnails[index].src = imgSrc;
                    thumbnails[index].onclick = () => changeImage(imgSrc);
                }
            });
        }
        
        window.currentProductId = productId;
        
        updateRelatedProducts(productId);
    } else {
        console.error('Product not found for ID:', productId);
        window.location.href = 'products.html';
    }
}

// Change main product image
function changeImage(imageSrc) {
    document.getElementById('mainProductImage').src = imageSrc;
    
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        if (thumb.src.includes(imageSrc)) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
}

// related products 
function updateRelatedProducts(currentProductId) {
    // Get products from the same category
    const currentProduct = products.find(p => p.id == currentProductId);
    const relatedProducts = products.filter(p => 
        p.id != currentProductId
    ).slice(0, 3); // Get max 3 related products (any category)
    
    // related products section
    const relatedContainer = document.querySelector('.related-products-grid');
    if (relatedContainer && relatedProducts.length > 0) {
        relatedContainer.innerHTML = relatedProducts.map(product => `
            <div class="product-card-small">
                <img src="${product.image}" alt="${product.name}">
                <h4>${product.name}</h4>
                <p>£${product.price.toFixed(2)}</p>
                <a href="productdetails.html?id=${product.id}" class="hero-btn">View Details</a>
            </div>
        `).join('');
    }
}

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

// Add to cart 
function addToCart() {
    const productId = window.currentProductId;
    const quantity = parseInt(document.getElementById('quantity').value);
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    const product = products.find(p => p.id == productId);
    
    if (product) {
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
        
        localStorage.setItem('cart', JSON.stringify(cart));
        
        alert(`${quantity} x ${product.name} has been added to your cart!`);
        
        document.getElementById('quantity').value = 1;
        
        updateCartCount();
    }
}

// Buy now 
function buyNow() {
    addToCart();
    // Redirect to cart page
    window.location.href = 'cart.html';
}

// Update cart 
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    
    const counter = document.querySelector('.cart-count');
    if (counter) {
        counter.textContent = count;
        counter.style.display = count > 0 ? 'flex' : 'none';
    }
}

updateCartCount();

// -------------------------------
// FAVOURITE SYSTEM FOR DETAILS PAGE
// -------------------------------
function addToFavouritesDetails() {
    const productId = Number(window.currentProductId);

    let favourites = JSON.parse(localStorage.getItem("favourites")) || [];

    if (!favourites.includes(productId)) {
        favourites.push(productId);
        localStorage.setItem("favourites", JSON.stringify(favourites));
        showFavouriteToast("Added to favourites!");
    } else {
        showFavouriteToast("Already in favourites!");
    }
}

// Toast popup
function showFavouriteToast(message) {
    let toast = document.createElement("div");
    toast.className = "favourite-toast";
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 2500);
}
