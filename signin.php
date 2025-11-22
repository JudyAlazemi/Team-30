<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
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

        <!-- LOGO REDIRECT (EDIT to your home file) -->
        <a href="home.php">
            <img src="assets/images/sabillogowhite.png" class="logo" alt="Sabil Logo">
        </a>

    </header>


    <!-- SIDE MENU -->
    <div id="side-menu" class="side-menu">
        <button id="close-menu" class="close-btn">✕</button>

        <nav class="menu-links">

            <!-- EDIT THESE TO REAL PAGES -->
            <a href="home.php">Option 1 <!-- EDIT HERE --></a>
            <a href="#">Option 2 <!-- EDIT HERE --></a>
            <a href="#">Option 3 <!-- EDIT HERE --></a>
            <a href="#">Option 4 <!-- EDIT HERE --></a>
            <a href="#">Option 5 <!-- EDIT HERE --></a>
            <a href="#">Option 6 <!-- EDIT HERE --></a>

        </nav>
    </div>

    <div id="overlay" class="overlay"></div>


    <!-- PAGE CONTENT -->
    <main class="content">

        <h1>Welcome back</h1>
        <p class="subtitle">Log in to continue your shopping experience</p>

        <form class="auth-form">

            <!-- Email -->
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="john@example.com">
            </div>

            <!-- Password -->
            <div class="form-group">
                <div class="flex-row-between">
                    <label>Password</label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <div class="password-wrapper">
                    <input id="password" type="password" placeholder="••••••••">
                    <button type="button" class="toggle-password" data-target="password">
                        <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Log In Button -->
            <button class="btn-primary">Log In</button>

            <!-- Sign up link -->
            <p class="signin">
                Don’t have an account?
                <a href="signup.php">Sign up</a>
            </p>


            <script src="assets/js/validate.js" defer></script>


        </form>

    </main>

</body>
</html>
