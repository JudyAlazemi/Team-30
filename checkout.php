<?php
if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . "/backend/config/session.php";
}

/**
 * --------- ORDER API (POST JSON) ----------
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $dbCandidates = [
        __DIR__ . "/backend/config/db.php",
        __DIR__ . "/config/db.php",
        __DIR__ . "/db.php",
    ];

    $dbLoaded = false;
    foreach ($dbCandidates as $p) {
        if (file_exists($p)) {
            require_once $p;
            $dbLoaded = true;
            break;
        }
    }

    if (!$dbLoaded || !isset($conn)) {
        http_response_code(500);
        echo json_encode([
            "ok" => false,
            "error" => "Database connection failed"
        ]);
        exit;
    }

    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode([
            "ok" => false,
            "error" => "Please login first"
        ]);
        exit;
    }

    $userId = (int) $_SESSION['user_id'];

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode([
            "ok" => false,
            "error" => "Invalid JSON body"
        ]);
        exit;
    }

    $items = $data['items'] ?? [];
    $shippingMethod = $data['shipping_method'] ?? 'standard';

    if (!is_array($items) || count($items) === 0) {
        http_response_code(400);
        echo json_encode([
            "ok" => false,
            "error" => "Cart is empty"
        ]);
        exit;
    }

    $requested = [];
    foreach ($items as $it) {
        $pid = (int) ($it['id'] ?? $it['product_id'] ?? 0);
        $qty = (int) ($it['qty'] ?? $it['quantity'] ?? 0);

        if ($pid <= 0 || $qty <= 0) {
            continue;
        }

        $requested[$pid] = ($requested[$pid] ?? 0) + $qty;
    }

    if (count($requested) === 0) {
        http_response_code(400);
        echo json_encode([
            "ok" => false,
            "error" => "Invalid cart items format"
        ]);
        exit;
    }

    try {
        $conn->begin_transaction();

        $cleanItems = [];
        $subtotal = 0.0;

        foreach ($requested as $pid => $qty) {
            $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $p = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$p) {
                throw new Exception("Product #{$pid} not found");
            }

            if ((int) $p['stock'] < $qty) {
                throw new Exception("Not enough stock for {$p['name']}");
            }

            $price = (float) $p['price'];
            $subtotal += $price * $qty;

            $cleanItems[] = [
                "product_id" => (int) $p['id'],
                "qty" => $qty,
                "price" => $price
            ];
        }

        $shipping = ($subtotal >= 40) ? 0.0 : 4.99;
        if ($shippingMethod === 'express') {
            $shipping = ($subtotal >= 40) ? 0.0 : 7.99;
        }

        $tax = round($subtotal * 0.08, 2);
        $total = round($subtotal + $shipping + $tax, 2);

        $status = "processing";
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ids", $userId, $total, $status);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();

        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        foreach ($cleanItems as $it) {
            $pid = (int) $it['product_id'];
            $qty = (int) $it['qty'];
            $price = (float) $it['price'];

            $stmtItem->bind_param("iiid", $orderId, $pid, $qty, $price);
            $stmtItem->execute();

            $stmtStock->bind_param("ii", $qty, $pid);
            $stmtStock->execute();
        }

        $stmtItem->close();
        $stmtStock->close();

        $conn->commit();

        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        echo json_encode([
            "ok" => true,
            "order_id" => $orderId,
            "total" => $total
        ]);
        exit;

    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
        }

        http_response_code(500);
        echo json_encode([
            "ok" => false,
            "error" => $e->getMessage()
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout - Sabil</title>

  <link rel="stylesheet" href="assets/css/style.css" />

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --color-primary: #303C30;
      --color-secondary: #4E4138;
      --color-light: #F4EEE9;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
      color: var(--color-primary);
      background-color: #ffffff;
      line-height: 1.6;
    }

    .container { max-width: 1280px; margin: 0 auto; padding: 3rem 1rem; }

    h1 {
      font-size: 2.5rem;
      font-weight: 300;
      line-height: 1.2;
      letter-spacing: -0.02em;
      color: var(--color-primary);
      margin-bottom: 0.5rem;
    }

    h2 {
      font-size: 1.75rem;
      font-weight: 400;
      line-height: 1.3;
      letter-spacing: -0.01em;
      color: var(--color-primary);
      margin-bottom: 1.5rem;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--color-primary);
      opacity: 0.7;
      text-decoration: none;
      margin-bottom: 1rem;
      transition: opacity 0.3s;
    }

    .back-link:hover { opacity: 1; }

    .secure-badge {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      opacity: 0.7;
      margin-top: 0.5rem;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
      margin-top: 2rem;
    }

    @media (min-width: 1024px) {
      .grid { grid-template-columns: 2fr 1fr; }
    }

    .form-section {
      background-color: var(--color-light);
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
    }

    .form-group { margin-bottom: 1rem; }

    .form-row {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    @media (min-width: 768px) {
      .form-row.two-col { grid-template-columns: 1fr 1fr; }
      .form-row.three-col { grid-template-columns: 1fr 1fr 1fr; }
    }

    label {
      display: block;
      color: var(--color-primary);
      margin-bottom: 0.5rem;
      font-weight: 400;
    }

    input, select {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 2px solid var(--color-secondary);
      border-radius: 8px;
      font-size: 1rem;
      color: var(--color-primary);
      background-color: white;
      transition: border-color 0.3s;
    }

    input:focus, select:focus {
      outline: none;
      border-color: var(--color-secondary);
      opacity: 0.7;
    }

    .shipping-option {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem;
      background-color: white;
      border: 2px solid transparent;
      border-radius: 8px;
      cursor: pointer;
      margin-bottom: 0.75rem;
      transition: all 0.3s;
    }

    .shipping-option:hover { opacity: 0.7; }
    .shipping-option.active { border-color: var(--color-secondary); }
    .shipping-option input[type="radio"] { width: auto; margin-right: 0.75rem; }
    .shipping-left { display: flex; align-items: center; }

    .shipping-details p:first-child {
      color: var(--color-primary);
      font-weight: 400;
    }

    .shipping-details p:last-child {
      color: var(--color-primary);
      opacity: 0.7;
      font-size: 0.875rem;
    }

    .payment-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .payment-tab {
      flex: 1;
      padding: 0.75rem 1rem;
      border: 2px solid var(--color-secondary);
      background-color: white;
      color: var(--color-secondary);
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 500;
      transition: all 0.3s;
    }

    .payment-tab.active {
      background-color: var(--color-secondary);
      color: white;
    }

    .paypal-notice {
      padding: 1.5rem;
      background-color: white;
      border-radius: 8px;
      text-align: center;
      color: var(--color-primary);
    }

    .summary-box {
      background-color: var(--color-light);
      padding: 1.5rem;
      border-radius: 8px;
      position: sticky;
      top: 1.5rem;
    }

    .order-items {
      padding-bottom: 1.5rem;
      border-bottom: 1px solid var(--color-secondary);
      margin-bottom: 1.5rem;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
      gap: 1rem;
    }

    .order-item p:last-child { font-weight: 400; }
    .item-qty { opacity: 0.7; font-size: 0.875rem; }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .summary-row span:first-child { opacity: 0.7; }

    .summary-total {
      border-top: 1px solid var(--color-secondary);
      padding-top: 1rem;
      margin-top: 1rem;
    }

    .summary-total span {
      font-weight: 500;
      opacity: 1 !important;
    }

    .btn-primary {
      width: 100%;
      background-color: var(--color-secondary);
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 500;
      transition: opacity 0.3s;
      margin-bottom: 1rem;
    }

    .btn-primary:hover:not(:disabled) { opacity: 0.9; }
    .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

    .terms-text {
      text-align: center;
      opacity: 0.7;
      color: var(--color-primary);
      font-size: 0.875rem;
    }

    .error-message {
      color: #dc2626;
      padding: 1rem;
      background: #fee2e2;
      border-radius: 8px;
      margin: 1rem 0;
      display: none;
    }

    .success-message {
      color: #907867;
      padding: 1rem;
      background: rgba(144, 120, 103, 0.12);
      border-radius: 8px;
      margin: 1rem 0;
      display: none;
    }

    .topbar .actions {
      display: flex;
      align-items: center;
      gap: 18px;
    }

    #accountNavSlot {
      display: flex;
      align-items: center;
      gap: 16px;
      white-space: nowrap;
    }

    #accountNavSlot .action {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
  </style>
</head>

<body>

  
<?php include __DIR__ . "/partials/navigation.php"; ?>


  <div class="container">
    <div class="header">
      <a href="cart.php" class="back-link">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Cart
      </a>

      <h1>Checkout</h1>

      <div class="secure-badge">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
        <p>Secure checkout</p>
      </div>
    </div>

    <div id="error-message" class="error-message"></div>
    <div id="success-message" class="success-message"></div>

    <form id="checkout-form">
      <div class="grid">
        <div>

          <div class="form-section">
            <h2>Contact Information</h2>
            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" required placeholder="john@example.com">
            </div>
            <div class="form-group">
              <label for="phone">Phone Number *</label>
              <input type="tel" id="phone" name="phone" required placeholder="+44 7484 557321">
            </div>
          </div>

          <div class="form-section">
            <h2>Shipping Address</h2>

            <div class="form-row two-col">
              <div class="form-group">
                <label for="firstName">First Name *</label>
                <input type="text" id="firstName" name="firstName" required>
              </div>
              <div class="form-group">
                <label for="lastName">Last Name *</label>
                <input type="text" id="lastName" name="lastName" required>
              </div>
            </div>

            <div class="form-group">
              <label for="address">Street Address *</label>
              <input type="text" id="address" name="address" required placeholder="123 Main Street">
            </div>

            <div class="form-group">
              <label for="apartment">Apartment, Suite, etc. (Optional)</label>
              <input type="text" id="apartment" name="apartment" placeholder="Apt 4B">
            </div>

            <div class="form-row three-col">
              <div class="form-group">
                <label for="city">City *</label>
                <input type="text" id="city" name="city" required>
              </div>
              <div class="form-group">
                <label for="state">State/County *</label>
                <input type="text" id="state" name="state" required>
              </div>
              <div class="form-group">
                <label for="zipCode">Postal Code *</label>
                <input type="text" id="zipCode" name="zipCode" required>
              </div>
            </div>

            <div class="form-group">
              <label for="country">Country *</label>
              <select id="country" name="country" required>
                <option value="">Select a country</option>
                <option value="UK">United Kingdom</option>
                <option value="US">United States</option>
                <option value="CA">Canada</option>
                <option value="AU">Australia</option>
              </select>
            </div>
          </div>

          <div class="form-section">
            <h2>Shipping Method</h2>

            <div class="shipping-option active" onclick="selectShipping('standard', event)">
              <div class="shipping-left">
                <input type="radio" name="shipping" value="standard" checked>
                <div class="shipping-details">
                  <p>Standard Shipping</p>
                  <p>5-7 business days</p>
                </div>
              </div>
              <p>£4.99</p>
            </div>

            <div class="shipping-option" onclick="selectShipping('express', event)">
              <div class="shipping-left">
                <input type="radio" name="shipping" value="express">
                <div class="shipping-details">
                  <p>Express Shipping</p>
                  <p>2-3 business days</p>
                </div>
              </div>
              <p>£7.99</p>
            </div>
          </div>

          <div class="form-section">
            <h2>Payment Method</h2>

            <div class="payment-tabs">
              <button type="button" class="payment-tab active" onclick="selectPayment('card', event)">Credit/Debit Card</button>
              <button type="button" class="payment-tab" onclick="selectPayment('paypal', event)">PayPal</button>
            </div>

            <div id="card-payment">
              <div class="form-group">
                <label for="cardNumber">Card Number *</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" required>
              </div>

              <div class="form-group">
                <label for="cardName">Name on Card *</label>
                <input type="text" id="cardName" name="cardName" placeholder="John Doe" required>
              </div>

              <div class="form-row two-col">
                <div class="form-group">
                  <label for="expiry">Expiry Date *</label>
                  <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                  <label for="cvv">CVV *</label>
                  <input type="text" id="cvv" name="cvv" placeholder="123" required>
                </div>
              </div>
            </div>

            <div id="paypal-payment" style="display:none;">
              <div class="paypal-notice">
                <p>You will be redirected to PayPal to complete your purchase.</p>
              </div>
            </div>
          </div>

        </div>

        <div>
          <div class="summary-box">
            <h2>Order Summary</h2>

            <div id="order-items" class="order-items"></div>

            <div class="summary-row">
              <span>Subtotal</span>
              <span id="subtotal">£0.00</span>
            </div>

            <div class="summary-row">
              <span>Shipping</span>
              <span id="shipping-cost">£0.00</span>
            </div>

            <div class="summary-row">
              <span>Tax (8%)</span>
              <span id="tax">£0.00</span>
            </div>

            <div class="summary-row summary-total">
              <span>Total</span>
              <span id="total-cost">£0.00</span>
            </div>

            <button type="submit" id="place-order-btn" class="btn-primary">Place Order</button>

            <p class="terms-text">By placing your order, you agree to our terms and conditions.</p>
          </div>
        </div>

      </div>
    </form>
  </div>

  <footer class="site-footer">
    <!-- your footer -->
  </footer>

  <script src="product-data.php?v=<?= time() ?>"></script>

  <script>
    const FREE_LIMIT = 40;
    const STANDARD_SHIPPING = 4.99;
    const EXPRESS_SHIPPING = 7.99;

    function money(n) {
      return "£" + (Math.round((Number(n) || 0) * 100) / 100).toFixed(2);
    }

    async function updateNavbar() {
      const slot = document.getElementById('accountNavSlot');
      if (!slot) return;

      try {
        const res = await fetch('check_login.php', {
          cache: 'no-store',
          credentials: 'same-origin'
        });

        if (!res.ok) throw new Error('Network response was not ok');

        const data = await res.json();

        if (data.loggedIn && data.role === "admin") {
          slot.innerHTML = `
            <a class="action" href="admin_dashboard.php" role="button">
              <img class="icon" src="assets/images/user.png" alt="My Account" />
              <span class="action-text">My Account</span>
            </a>
            <a class="action" href="admin_logout.php" role="button">
              <span class="action-text">Logout</span>
            </a>
          `;
        } else if (data.loggedIn && data.role === "customer") {
          slot.innerHTML = `
            <a class="action" href="customer_dashboard.php" role="button">
              <img class="icon" src="assets/images/user.png" alt="My Account" />
              <span class="action-text">My Account</span>
            </a>
            <a class="action" href="logout.php" role="button">
              <span class="action-text">Logout</span>
            </a>
          `;
        } else {
          slot.innerHTML = `
            <a class="action" href="login.html?redirect=checkout.php" role="button">
              <img class="icon" src="assets/images/sign-in.png" alt="Sign in" />
              <span class="action-text">Sign in</span>
            </a>
          `;
        }
      } catch (e) {
        slot.innerHTML = `
          <a class="action" href="login.html?redirect=checkout.php" role="button">
            <img class="icon" src="assets/images/sign-in.png" alt="Sign in" />
            <span class="action-text">Sign in</span>
          </a>
        `;
      }
    }

    function getLocalCart() {
      try {
        return JSON.parse(localStorage.getItem('cart')) || [];
      } catch (e) {
        console.error('Error reading cart:', e);
        return [];
      }
    }

    function getFullCart() {
      const cart = getLocalCart();
      const products = Array.isArray(window.productsData) ? window.productsData : [];

      return cart.map(item => {
        const productId = item.id ?? item.product_id;
        const product = products.find(p => String(p.id) === String(productId));
        if (!product) return null;

        const quantity = Math.max(1, parseInt(item.quantity ?? item.qty ?? 1, 10) || 1);
        const safePrice = parseFloat(product.price) || 0;

        return {
          id: parseInt(product.id, 10),
          product_id: parseInt(product.id, 10),
          name: product.name ?? 'Unnamed Product',
          image: product.image ?? 'assets/images/logo.png',
          price: safePrice,
          quantity: quantity,
          qty: quantity,
          subtotal: safePrice * quantity
        };
      }).filter(Boolean);
    }

    function calculateTotals(items, shippingMethod = 'standard') {
      const subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);

      let shipping = 0;
      if (items.length) {
        shipping = subtotal >= FREE_LIMIT
          ? 0
          : (shippingMethod === 'express' ? EXPRESS_SHIPPING : STANDARD_SHIPPING);
      }

      const tax = subtotal * 0.08;
      const total = subtotal + shipping + tax;

      return { subtotal, shipping, tax, total };
    }

    function renderOrderSummary() {
      const items = getFullCart();
      const itemsContainer = document.getElementById('order-items');
      const subtotalEl = document.getElementById('subtotal');
      const shippingEl = document.getElementById('shipping-cost');
      const taxEl = document.getElementById('tax');
      const totalEl = document.getElementById('total-cost');
      const placeOrderBtn = document.getElementById('place-order-btn');

      if (!items.length) {
        itemsContainer.innerHTML = '<p style="text-align:center; padding:2rem;">Your cart is empty</p>';
        subtotalEl.textContent = money(0);
        shippingEl.textContent = money(0);
        taxEl.textContent = money(0);
        totalEl.textContent = money(0);
        placeOrderBtn.disabled = true;
        return;
      }

      placeOrderBtn.disabled = false;

      const shippingMethod = document.querySelector('input[name="shipping"]:checked')?.value || 'standard';
      const totals = calculateTotals(items, shippingMethod);

      itemsContainer.innerHTML = items.map(item => `
        <div class="order-item">
          <div>
            <p>${item.name}</p>
            <p class="item-qty">Qty: ${item.quantity}</p>
          </div>
          <p>${money(item.subtotal)}</p>
        </div>
      `).join('');

      subtotalEl.textContent = money(totals.subtotal);
      shippingEl.textContent = totals.shipping === 0 ? 'FREE' : money(totals.shipping);
      taxEl.textContent = money(totals.tax);
      totalEl.textContent = money(totals.total);

      window.currentTotals = totals;
    }

    window.selectShipping = function(method, event) {
      document.querySelectorAll('.shipping-option').forEach(opt => opt.classList.remove('active'));
      event.currentTarget.classList.add('active');

      const radio = event.currentTarget.querySelector('input[type="radio"]');
      if (radio) {
        radio.checked = true;
      }

      renderOrderSummary();
    };

    window.selectPayment = function(method, event) {
      document.querySelectorAll('.payment-tab').forEach(tab => tab.classList.remove('active'));
      event.currentTarget.classList.add('active');

      const cardPayment = document.getElementById('card-payment');
      const paypalPayment = document.getElementById('paypal-payment');

      const cardNumber = document.getElementById('cardNumber');
      const cardName = document.getElementById('cardName');
      const expiry = document.getElementById('expiry');
      const cvv = document.getElementById('cvv');

      if (method === 'card') {
        cardPayment.style.display = 'block';
        paypalPayment.style.display = 'none';

        cardNumber.required = true;
        cardName.required = true;
        expiry.required = true;
        cvv.required = true;
      } else {
        cardPayment.style.display = 'none';
        paypalPayment.style.display = 'block';

        cardNumber.required = false;
        cardName.required = false;
        expiry.required = false;
        cvv.required = false;
      }
    };

    async function placeOrder() {
      const btn = document.getElementById('place-order-btn');
      const errorDiv = document.getElementById('error-message');
      const successDiv = document.getElementById('success-message');

      const form = document.getElementById('checkout-form');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const items = getFullCart();
      if (items.length === 0) {
        errorDiv.textContent = 'Your cart is empty';
        errorDiv.style.display = 'block';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Processing...';
      errorDiv.style.display = 'none';
      successDiv.style.display = 'none';

      try {
        const shippingMethod = document.querySelector('input[name="shipping"]:checked')?.value || 'standard';
        const totals = window.currentTotals || calculateTotals(items, shippingMethod);

        const orderData = {
          items: items.map(item => ({
            id: item.id,
            product_id: item.id,
            quantity: item.quantity,
            qty: item.quantity,
            price: item.price
          })),
          shipping_method: shippingMethod,
          subtotal: totals.subtotal,
          shipping: totals.shipping,
          tax: totals.tax,
          total: totals.total,
          customer: {
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            address: document.getElementById('address').value,
            apartment: document.getElementById('apartment').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
            zipCode: document.getElementById('zipCode').value,
            country: document.getElementById('country').value
          }
        };

        const response = await fetch('checkout.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          credentials: 'same-origin',
          body: JSON.stringify(orderData)
        });

        const text = await response.text();
        let result = {};

        try {
          result = JSON.parse(text);
        } catch (e) {
          throw new Error('Invalid server response: ' + text);
        }

        if (!response.ok || !result.ok) {
          throw new Error(result.error || 'Failed to place order');
        }

        localStorage.removeItem('cart');

        successDiv.innerHTML = `
          <strong>Order placed successfully!</strong><br>
          Order #${result.order_id}<br>
          Total: ${money(result.total)}
        `;
        successDiv.style.display = 'block';

        document.getElementById('checkout-form').style.display = 'none';

        setTimeout(() => {
          window.location.href = 'customer_orders.php';
        }, 3000);

      } catch (error) {
        errorDiv.textContent = error.message;
        errorDiv.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Place Order';
      }
    }

    async function checkLogin() {
      try {
        const response = await fetch('check_login.php', {
          cache: 'no-store',
          credentials: 'same-origin'
        });

        const data = await response.json();

        if (!data.loggedIn) {
          alert('Please login to checkout');
          window.location.href = 'login.html?redirect=checkout.php';
        }
      } catch (error) {
        console.error('Login check failed:', error);
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      updateNavbar();
      checkLogin();
      renderOrderSummary();

      document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        placeOrder();
      });

      document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', renderOrderSummary);
      });
    });
  </script>

</body>
</html>