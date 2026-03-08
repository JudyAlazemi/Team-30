<?php
if (file_exists(__DIR__ . '/db.php')) {
    require_once __DIR__ . '/db.php';
}
?>
<!doctype html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>FAQ | Sabil</title>
    <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/static.css" />
    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>
 
      <link rel="icon" type="image/png" href="images/logo.png">

</head>
<body class="page-static">

<header class="topbar">
  <div class="topbar-inner">

    <!-- Left: Menu button -->
    <button class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false">
      <img class="icon icon--menu" src="assets/images/menu.png" alt="" />
      <img class="icon icon--close" src="assets/images/close.png" alt="" />
    </button>

    <!-- Center: Logo -->
    <a class="brand" href="index.php">
      <img class="brand-logo" src="assets/images/logo.png" alt="Sabil" />
    </a>

    <!-- Right: actions -->
    <nav class="actions" aria-label="Account & tools">

      <!-- USER -->
      <a id="userBtn" class="action" href="login.html" role="button" aria-pressed="false">
        <img
          id="userIcon"
          class="icon"
          src="assets/images/sign-in.png"
          alt="User"
          data-src-inactive="assets/images/sign-in.png"
          data-src-active="assets/images/user-shaded.png"
        />
        <span id="userText" class="action-text">Sign in</span>
      </a>

      <!-- SEARCH GROUP (one flex item) -->
      <div class="search-group">
        <a id="searchBtn" class="action" href="#">
          <img class="icon" src="assets/images/search.png" alt="Search" />
        </a>
        <input
          type="text"
          id="navSearchInput"
          class="nav-search-input"
          placeholder="Search..."
        />
      </div>

      <!-- FAVOURITE -->
      <a id="favBtn" class="action" href="favourites.php" role="button" aria-pressed="false">
        <img
          id="favIcon"
          class="icon"
          src="assets/images/favorite.png"
          alt="Favourite"
          data-src-inactive="assets/images/favorite.png"
          data-src-active="assets/images/favorite-shaded.png"
        />
      </a>

      <!-- BAG -->
      <a id="bagBtn" class="action" href="cart.html" role="button" aria-pressed="false">
        <img
          id="bagIcon"
          class="icon"
          src="assets/images/shopping-bag.png"
          alt="Shopping bag"
          data-src-inactive="assets/images/shopping-bag.png"
          data-src-active="assets/images/shopping-bag-filled.png"
        />
      </a>
    </nav>
  </div>
</header>

<!-- MENU DRAWER (left-side) -->
<div id="menuDrawer" class="drawer" aria-hidden="true">
  <div class="drawer__backdrop" data-close-drawer></div>

  <aside class="drawer__panel" role="dialog" aria-modal="true" aria-label="Site menu">
    <nav class="drawer__nav">
      <a href="products.html">Shop all</a>
      <a href="cart.html">Cart</a>
      <a href="favourites.php">Favourites</a>
      <a href="contactus.php">Contact us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
  
    </nav>
  </aside>
</div>


  <!-- Two-column layout -->
  <section class="faq-main" id="faq-main">
      <div class="faq-main-inner">

          <!-- LEFT: CATEGORY NAV -->
          <aside class="faq-categories" aria-label="FAQ categories">
              <h2 class="faq-categories-title">Browse by topic</h2>
              <ul class="faq-category-list">
                  <li><button class="faq-category-link is-active" data-faq-target="orders">Orders &amp; Shipping</button></li>
                  <li><button class="faq-category-link" data-faq-target="products">Fragrances &amp; Ingredients</button></li>
                  <li><button class="faq-category-link" data-faq-target="returns">Returns &amp; Exchanges</button></li>
                  <li><button class="faq-category-link" data-faq-target="other">Other questions</button></li>
              </ul>
          </aside>

          <!-- RIGHT: FAQ GROUPS -->
          <div class="faq-groups">

              <!-- GROUP: ORDERS & SHIPPING -->
              <section class="faq-group" id="faq-group-orders" data-faq-group="orders">
                  <h2 class="faq-group-title">Orders &amp; Shipping</h2>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-order-1">
                          <span class="faq-question-text">Where do you ship and how long will it take?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-order-1">
                          <div class="faq-answer-inner">
                              <p>
                                  We currently ship to the UK and most European countries. Orders are usually processed
                                  within 1–2 business days. Delivery timing depends on your location and the shipping
                                  method chosen at checkout, but most parcels arrive within 2–7 business days once
                                  dispatched.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-order-2">
                          <span class="faq-question-text">How much is shipping?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-order-2">
                          <div class="faq-answer-inner">
                              <p>
                                  Shipping rates are calculated at checkout based on your delivery address and order
                                  value. We often offer free shipping above a certain spend threshold – keep an eye on
                                  the message at the top of the cart to see how close you are.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-order-3">
                          <span class="faq-question-text">Will I need to pay customs or duties?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-order-3">
                          <div class="faq-answer-inner">
                              <p>
                                  Any customs, duties or import taxes are set by your local authorities and are the
                                  responsibility of the recipient. These are not controlled by us and may vary from
                                  country to country.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-order-4">
                          <span class="faq-question-text">Can I change or cancel my order?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-order-4">
                          <div class="faq-answer-inner">
                              <p>
                                  If you need to update your order, please contact us as soon as possible with your
                                  order number. We’ll do our best to accommodate changes before your parcel is
                                  dispatched, but we can’t guarantee amendments once the order is processing.
                              </p>
                          </div>
                      </div>
                  </div>
              </section>

              <!-- GROUP: FRAGRANCES & INGREDIENTS -->
              <section class="faq-group" id="faq-group-products" data-faq-group="products">
                  <h2 class="faq-group-title">Fragrances &amp; Ingredients</h2>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-product-1">
                          <span class="faq-question-text">Are your perfumes vegan and cruelty-free?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-product-1">
                          <div class="faq-answer-inner">
                              <p>
                                  Yes. Our fragrances are formulated without animal-derived ingredients and are not
                                  tested on animals. We also work with suppliers who share this commitment.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-product-2">
                          <span class="faq-question-text">What does “natural” mean for Sabil fragrances?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-product-2">
                          <div class="faq-answer-inner">
                              <p>
                                  Our focus is on high-quality ingredients of natural origin wherever possible,
                                  combined with safe, carefully selected aroma molecules when needed for performance
                                  and stability. Each formula is designed to balance beauty, safety and longevity.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-product-3">
                          <span class="faq-question-text">How strong are your perfumes and how long do they last?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-product-3">
                          <div class="faq-answer-inner">
                              <p>
                                  Longevity depends on your skin, environment and how much you apply. As a guide,
                                  expect our Eau de Parfum to last several hours on the skin, with the most intense
                                  notes present in the first few hours and softer nuances appearing as it wears.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-product-4">
                          <span class="faq-question-text">How should I store my fragrance?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-product-4">
                          <div class="faq-answer-inner">
                              <p>
                                  To keep your perfume at its best, store it upright in a cool, dry place away from
                                  direct sunlight and extreme temperature changes. Avoid keeping it in the bathroom,
                                  where heat and humidity can affect the formula over time.
                              </p>
                          </div>
                      </div>
                  </div>
              </section>

              <!-- GROUP: RETURNS & EXCHANGES -->
              <section class="faq-group" id="faq-group-returns" data-faq-group="returns">
                  <h2 class="faq-group-title">Returns &amp; Exchanges</h2>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-return-1">
                          <span class="faq-question-text">What is your returns policy?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-return-1">
                          <div class="faq-answer-inner">
                              <p>
                                  For hygiene and safety reasons, we can only accept returns of unopened, unused
                                  products in their original packaging, within a set number of days from delivery
                                  (see our full Returns Policy for exact details). Please contact us before sending
                                  anything back so we can provide instructions.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-return-2">
                          <span class="faq-question-text">My order arrived damaged – what should I do?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-return-2">
                          <div class="faq-answer-inner">
                              <p>
                                  We’re sorry if anything arrives less than perfect. Please email us with your order
                                  number, a brief description of the issue, and clear photos of the packaging and
                                  product. Our team will review and arrange a replacement or refund where appropriate.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-return-3">
                          <span class="faq-question-text">Can I return a scent I don’t like?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-return-3">
                          <div class="faq-answer-inner">
                              <p>
                                  Fragrance is very personal, so we always recommend exploring our discovery sizes
                                  before committing to a full bottle. At the moment we’re unable to accept returns of
                                  used fragrances simply because the scent isn’t to your taste.
                              </p>
                          </div>
                      </div>
                  </div>
              </section>

              <!-- GROUP: OTHER QUESTIONS -->
              <section class="faq-group" id="faq-group-other" data-faq-group="other">
                  <h2 class="faq-group-title">Other questions</h2>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-other-1">
                          <span class="faq-question-text">Do you offer samples or a discovery set?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-other-1">
                          <div class="faq-answer-inner">
                              <p>
                                  Yes. Our discovery sizes are designed so you can live with each scent before
                                  choosing your favourite. Check the “Discovery” or “Minis” section of the shop to
                                  see what’s currently available.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-other-2">
                          <span class="faq-question-text">Can I send an order as a gift?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-other-2">
                          <div class="faq-answer-inner">
                              <p>
                                  Absolutely. You can enter the recipient’s address at checkout and, where available,
                                  add a gift note. Prices are not included inside the parcel.
                              </p>
                          </div>
                      </div>
                  </div>

                  <div class="faq-item">
                      <button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-other-3">
                          <span class="faq-question-text">How can I contact you if my question isn’t listed?</span>
                          <span class="faq-icon" aria-hidden="true">+</span>
                      </button>
                      <div class="faq-answer" id="faq-other-3">
                          <div class="faq-answer-inner">
                              <p>
                                  You can reach us via the contact form on our website or by emailing
                                  <a href="mailto:info@sabilfragrances.com">info@sabilfragrances.com</a>. We aim to
                                  reply within 1–2 business days.
                              </p>
                          </div>
                      </div>
                  </div>
              </section>

          </div><!-- /.faq-groups -->
      </div><!-- /.faq-main-inner -->
  </section>


</div>
</main>

<footer class="site-footer">
  <div class="footer-container">

    <!-- Newsletter -->
    <div class="footer-newsletter">
      <h3>Join our newsletter</h3>
      <p>Get personalized updates, launch news, and exclusive access just for you.</p>

      <form id="newsletterForm">
        <input 
          type="email"
          id="newsletterEmail"
          name="email"
          placeholder="Enter your email"
          required
        >
        <button type="submit">Subscribe</button>
        <p id="newsletterMsg" style="margin-top:10px;"></p>
      </form>
    </div>

    <!-- Column 1 -->
    <div class="footer-links">
      <a href="contactus.php">Contact Us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About Us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
    </div>

    <!-- Column 2 -->
    <div class="footer-links">
      <a href="products.html">Order</a>
      <a href="cart.html">Shopping Cart</a>
      <a href="favourites.php">Favourites</a>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© <?php echo date('Y'); ?> Sabil. All rights reserved.</p>
  </div>
</footer>

<script>
// FAQ accordion behaviour
document.querySelectorAll('.faq-question').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var expanded = this.getAttribute('aria-expanded') === 'true';
        var answerId = this.getAttribute('aria-controls');
        var answerEl = document.getElementById(answerId);

        this.setAttribute('aria-expanded', expanded ? 'false' : 'true');

        if (answerEl) {
            if (!expanded) {
                answerEl.classList.add('is-open');
            } else {
                answerEl.classList.remove('is-open');
            }
        }
    });
});

// Category filter behaviour
var categoryButtons = document.querySelectorAll('.faq-category-link');
var groups = document.querySelectorAll('.faq-group');

categoryButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
        var target = this.getAttribute('data-faq-target');

        categoryButtons.forEach(function (b) { b.classList.remove('is-active'); });
        this.classList.add('is-active');

        groups.forEach(function (group) {
            if (group.getAttribute('data-faq-group') === target) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
            }
        });

        var main = document.getElementById('faq-main');
        if (main) {
            var rect = main.getBoundingClientRect();
            window.scrollTo({
                top: window.scrollY + rect.top - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Initial state – show only "orders" group
(function initFaq() {
    var active = document.querySelector('.faq-category-link.is-active');
    if (active) {
        var target = active.getAttribute('data-faq-target');
        groups.forEach(function (group) {
            group.style.display = (group.getAttribute('data-faq-group') === target) ? 'block' : 'none';
        });
    }
})();
</script>

</body>
</html>
