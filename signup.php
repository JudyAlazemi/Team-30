<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
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
        <a href="home.php"> <!-- EDIT: change home.php to your real homepage file -->
            <img src="assets/images/sabillogowhite.png" class="logo" alt="Sabil Logo">
        </a>

    </header>

    <!-- SIDE MENU -->
    <div id="side-menu" class="side-menu">
        <button id="close-menu" class="close-btn">✕</button>

        <nav class="menu-links">

            <!-- EDIT THESE LINKS TO MATCH YOUR PAGES -->
            <a href="home.php">Option 1 <!-- EDIT WITH home.php or whatever page --></a>
            <a href="#">Option 2 <!-- EDIT WITH page2.php --></a>
            <a href="#">Option 3 <!-- EDIT WITH page3.php --></a>
            <a href="#">Option 4 <!-- EDIT WITH page4.php --></a>
            <a href="#">Option 5 <!-- EDIT WITH page5.php --></a>
            <a href="#">Option 6 <!-- EDIT WITH page6.php --></a>

        </nav>
    </div>

    <div id="overlay" class="overlay"></div>

    <!-- PAGE CONTENT -->
    <main class="content">

        <h1>Create your account</h1>
        <p class="subtitle">Join thousands of happy shoppers today</p>

        <form class="auth-form">

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

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label>Password</label>
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

            <div class="form-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input id="confirmPassword" type="password" placeholder="••••••••">
                    <button type="button" class="toggle-password" data-target="confirmPassword">
                        <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button class="btn-primary">Create Account</button>

            <p class="legal">
                By signing up, you agree to our 
                <a href="#">Terms of Service</a> and 
                <a href="#">Privacy Policy</a>
            </p>

            <p class="signin">
                Already have an account? 
                <a href="signin.php">Sign in</a> <!-- already redirects correctly -->
            </p>


            <script src="assets/js/validate.js" defer></script>


        </form>

    </main>

</body>
</html>
