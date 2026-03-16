<?php
// cart.php  ✅ one file = (1) JSON API when ?action=...  (2) HTML page otherwise
require_once __DIR__ . "/backend/config/session.php";

$isApi = isset($_GET['action']) || isset($_POST['action']);

if ($isApi) {
  header("Content-Type: application/json");

  // DB connection (support both structures)
  $dbPathA = __DIR__ . "/backend/config/db.php";
  $dbPathB = __DIR__ . "/config/db.php";
  $dbPathC = __DIR__ . "/db.php";

  if (file_exists($dbPathA)) {
      require_once $dbPathA;
  } elseif (file_exists($dbPathB)) {
      require_once $dbPathB;
  } elseif (file_exists($dbPathC)) {
      require_once $dbPathC;
  } else {
      http_response_code(500);
      echo json_encode(["ok" => false, "error" => "Database config not found"]);
      exit;
  }

  $user_id = $_SESSION['user_id'] ?? null;
  $action  = $_GET['action'] ?? $_POST['action'] ?? '';

  // If guest → tell them to login (for API actions)
  if (!$user_id) {
      echo json_encode([
        "ok" => false,
        "requireLogin" => true,
        "message" => "Please login to use the cart.",
        "loginUrl" => "login.html"
      ]);
      exit;
  }

  if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
  }

  switch ($action) {
      case 'add':
          $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
          $quantity   = (int)($_POST['quantity'] ?? $_GET['quantity'] ?? 1);

          if (!$product_id) {
              echo json_encode(["ok" => false, "error" => "Missing product_id"]);
              exit;
          }

          $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ?");
          $stmt->bind_param("i", $product_id);
          $stmt->execute();
          $product = $stmt->get_result()->fetch_assoc();

          if (!$product || (int)$product['stock'] < $quantity) {
              echo json_encode(["ok" => false, "error" => "Product unavailable or out of stock"]);
              exit;
          }

          $existingQty = isset($_SESSION['cart'][$product_id]) ? (int)$_SESSION['cart'][$product_id]['quantity'] : 0;
          $newQty = $existingQty + $quantity;

          if ($newQty > (int)$product['stock']) {
              echo json_encode(["ok" => false, "error" => "Not enough stock for this quantity"]);
              exit;
          }

          $_SESSION['cart'][$product_id] = [
              "product_id" => (int)$product['id'],
              "name"       => $product['name'],
              "price"      => (float)$product['price'],
              "quantity"   => (int)$newQty,
              "subtotal"   => (float)$newQty * (float)$product['price']
          ];

          echo json_encode(["ok" => true, "message" => "Item added to cart", "cart" => $_SESSION['cart']]);
          break;

      case 'remove':
          $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;

          if (!$product_id) {
              echo json_encode(["ok" => false, "error" => "Missing product_id"]);
              exit;
          }

          if (isset($_SESSION['cart'][$product_id])) {
              unset($_SESSION['cart'][$product_id]);
              echo json_encode(["ok" => true, "message" => "Item removed", "cart" => $_SESSION['cart']]);
          } else {
              echo json_encode(["ok" => false, "error" => "Item not found in cart"]);
          }
          break;

      case 'update':
          $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
          $quantity   = (int)($_POST['quantity'] ?? $_GET['quantity'] ?? 0);

          if (!$product_id || $quantity <= 0) {
              echo json_encode(["ok" => false, "error" => "Missing product_id or quantity"]);
              exit;
          }

          if (isset($_SESSION['cart'][$product_id])) {
              $stmt = $conn->prepare("SELECT stock, price, name FROM products WHERE id = ?");
              $stmt->bind_param("i", $product_id);
              $stmt->execute();
              $p = $stmt->get_result()->fetch_assoc();

              if (!$p) {
                  echo json_encode(["ok" => false, "error" => "Product not found"]);
                  exit;
              }

              if ((int)$p['stock'] < $quantity) {
                  echo json_encode(["ok" => false, "error" => "Not enough stock"]);
                  exit;
              }

              $_SESSION['cart'][$product_id]['quantity'] = (int)$quantity;
              $_SESSION['cart'][$product_id]['name'] = $p['name'];
              $_SESSION['cart'][$product_id]['price'] = (float)$p['price'];
              $_SESSION['cart'][$product_id]['subtotal'] = (float)$p['price'] * (int)$quantity;

              echo json_encode(["ok" => true, "message" => "Quantity updated", "cart" => $_SESSION['cart']]);
          } else {
              echo json_encode(["ok" => false, "error" => "Item not found in cart"]);
          }
          break;

      case 'clear':
          $_SESSION['cart'] = [];
          echo json_encode(["ok" => true, "message" => "Cart cleared", "cart" => []]);
          break;

      case 'get':
      default:
          $cart = $_SESSION['cart'] ?? [];
          $subtotal = array_sum(array_column($cart, 'subtotal'));
          echo json_encode(["ok" => true, "cart" => $cart, "subtotal" => $subtotal]);
          break;
  }

  if (isset($conn)) $conn->close();
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/darkmode.css">
<script defer src="assets/js/nav.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Sabil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

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

        h3 {
            font-size: 1.25rem;
            font-weight: 400;
            line-height: 1.4;
            color: var(--color-primary);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--color-primary);
            opacity: 0.7;
            margin-bottom: 2rem;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .cart-item {
            background-color: var(--color-light);
            padding: 1.5rem;
            border-radius: 8px;
            display: flex;
            gap: 1.5rem;
        }

        .item-image {
            width: 96px;
            height: 96px;
            flex-shrink: 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-price {
            color: var(--color-secondary);
            margin-bottom: 1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: 2px solid var(--color-secondary);
            background: white;
            color: var(--color-secondary);
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s;
        }

        .quantity-btn:hover {
            opacity: 0.7;
        }

        .item-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .remove-btn {
            color: #dc2626;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: flex-end;
            font-size: 0.875rem;
            transition: opacity 0.3s;
        }

        .remove-btn:hover {
            opacity: 0.7;
        }

        .summary-box {
            background-color: var(--color-light);
            padding: 1.5rem;
            border-radius: 8px;
            position: sticky;
            top: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .summary-row span:first-child {
            opacity: 0.7;
        }

        .summary-total {
            border-top: 1px solid var(--color-secondary);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .free-shipping-notice {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            color: var(--color-primary);
            opacity: 0.7;
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

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            width: 100%;
            background-color: transparent;
            color: var(--color-secondary);
            border: 2px solid var(--color-secondary);
            padding: 0.75rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .btn-secondary:hover {
            opacity: 0.7;
        }

        .empty-cart {
            background-color: var(--color-light);
            padding: 5rem 2rem;
            text-align: center;
            border-radius: 8px;
        }

        .empty-cart p {
            opacity: 0.7;
            margin-bottom: 1.5rem;

        .cart-items img {
             max-width: 96px; 
             height: 96px; 
             object-fit: cover; 
            }

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
    <h1>Shopping Cart</h1>
    <p class="subtitle"><span id="item-count">0</span></p>
  </div>

  <div class="grid">
    <div class="cart-items" id="cart-list"></div>

    <aside class="summary-box">
      <h2>Order Summary</h2>

      <div class="summary-row">
        <span>Subtotal</span>
        <span id="subtotal">£0.00</span>
      </div>

      <div class="summary-row">
        <span>Shipping</span>
        <span id="shipping">£0.00</span>
      </div>

      <div class="summary-row">
        <span>Tax</span>
        <span id="tax">£0.00</span>
      </div>

      <div class="summary-row summary-total">
        <span style="opacity:1; font-weight:500;">Total</span>
        <span id="total" style="font-weight:500;">£0.00</span>
      </div>

      <div class="free-shipping-notice" id="fsn" style="display:none;"></div>

      <button class="btn-primary" id="checkoutBtn" type="button">
        Proceed to Checkout
      </button>

      <a class="btn-secondary" href="products.php">Continue Shopping</a>
    </aside>
  </div>
</div>

<script src="assets/js/products-data.js"></script>
<script src="assets/js/cart.js"></script>

<script>
  // ✅ Navbar (My Account + Logout / Sign in)
  async function updateNavbar() {
    const slot = document.getElementById('accountNavSlot');
    if (!slot) return;

    try {
      const res = await fetch('check_login.php', { cache: 'no-store', credentials: 'same-origin' });
      if (!res.ok) throw new Error('Network response was not ok');
      const data = await res.json();

      if (data.loggedIn) {
        slot.innerHTML = `
          <a class="action account" href="customer_dashboard.php" role="button"
             style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;margin-right:8px;">
            <img class="icon" src="assets/images/user.png" alt="My Account" style="width:24px;height:24px;" />
            <span class="action-text" style="color: var(--text-dark);">My Account</span>
          </a>
          <a class="action" href="logout.php" role="button"
             style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
            <span class="action-text" style="color: var(--text-dark);">Logout</span>
          </a>
        `;
      } else {
        slot.innerHTML = `
          <a class="action account" href="login.html" role="button"
             style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
            <img class="icon" src="assets/images/sign-in.png" alt="Sign in" style="width:24px;height:24px;" />
            <span class="action-text" style="color: var(--text-dark);">Sign in</span>
          </a>
        `;
      }
    } catch (e) {
      slot.innerHTML = `
        <a class="action account" href="login.html" role="button"
           style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
          <img class="icon" src="assets/images/sign-in.png" alt="Sign in" style="width:24px;height:24px;" />
          <span class="action-text" style="color: var(--text-dark);">Sign in</span>
        </a>
      `;
    }
  }

  document.addEventListener('DOMContentLoaded', updateNavbar);
  window.addEventListener('pageshow', updateNavbar);
  setInterval(updateNavbar, 30000);
</script>
</body>
</html>
