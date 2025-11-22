<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/auth.css">
    <script src="assets/js/auth.js" defer></script>
</head>

<body>

    <!-- HEADER -->
    <header class="top-banner">

        <!-- HAMBURGER MENU BUTTON -->
        <button id="menu-btn" class="hamburger-btn">
            <img src="assets/images/hamburgermenu.png" class="hamburger-icon" alt="menu">
        </button>

        <!-- LOGO (redirects to home page) -->
        <a href="home.php"> <!-- TODO: change to real homepage -->
            <img src="assets/images/sabillogowhite.png" class="logo" alt="Sabil Logo">
        </a>

    </header>

    <!-- SIDE MENU -->
    <div id="side-menu" class="side-menu">
        <button id="close-menu" class="close-btn">✕</button>

        <nav class="menu-links">

            <!-- TODO: wire these when pages are made -->
            <a href="home.php">Option 1</a>
            <a href="#">Option 2</a>
            <a href="#">Option 3</a>
            <a href="#">Option 4</a>
            <a href="#">Option 5</a>
            <a href="#">Option 6</a>

        </nav>
    </div>

    <div id="overlay" class="overlay"></div>


    <!-- PAGE CONTENT -->
    <main class="content">

        <h1>Complete your order</h1>
        <p class="subtitle">Enter your shipping and payment details</p>

        <form class="auth-form">

            <!-- NAME -->
            <div class="two-cols">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" placeholder="John">
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" placeholder="Doe">
                </div>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="john@example.com">
            </div>

            <!-- ADDRESS -->
            <div class="form-group">
                <label>Street Address</label>
                <input type="text" placeholder="123 High Street">
            </div>

            <!-- CITY + POSTCODE -->
            <div class="two-cols">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" placeholder="Birmingham">
                </div>

                <div class="form-group">
                    <label>Postcode</label>
                    <input type="text" placeholder="B1 1AA">
                </div>
            </div>

            <!-- CARD NUMBER -->
            <div class="form-group">
                <label>Card Number</label>
                <input type="text" placeholder="1234 5678 9012 3456">
            </div>

            <!-- CARD NAME -->
            <div class="form-group">
                <label>Cardholder Name</label>
                <input type="text" placeholder="John Doe">
            </div>

            <!-- EXPIRY + CVV -->
            <div class="two-cols">
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="text" placeholder="MM/YY">
                </div>

                <div class="form-group">
                    <label>CVV</label>
                    <input type="password" placeholder="123">
                </div>
            </div>

            <!-- SUBMIT -->
            <button class="btn-primary">Place Order</button>

            <p class="legal">
                By placing this order, you agree to our 
                <a href="#">Terms of Service</a> and 
                <a href="#">Privacy Policy</a>
            </p>

            <script src="assets/js/validate.js" defer></script>


        </form>

    </main>

</body>
</html>
