<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Centralized Event Promotion</title>
@vite('resources/css/app.css')
<style>
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        height: 100%;
        color: #ffffff;
        background: linear-gradient(135deg, #001133, #003366, #0055aa, #0077cc);
        background-size: 400% 400%;
        animation: gradientMove 12s ease infinite;
        position: relative;
        overflow: hidden;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }


    /* Top bar */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 60px;
        color: #ffffff;
        position: relative;
        z-index: 1;
    }

    .top-bar h1 {
        font-size: 1.5rem;   /* smaller */
        font-weight: 200;    /* thinner */
        color: #ffffff;
    }

    nav a {
        margin-left: 30px;
        text-decoration: none;
        color: #cfd8dc;
        font-weight: 400;
        transition: color 0.3s ease;
    }

    nav a:hover {
        color: #88aaff;
    }

    /* Layout wrapper */
    .layout {
        display: flex;
        height: calc(100vh - 80px);
        position: relative;
        z-index: 1;
    }

    /* Left event element */
    .event-left {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .event-left img {
        max-width: 600px; /* slightly smaller */
        opacity: 1;       /* fully visible */
    }

    /* Main content */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
        padding: 80px;
        text-align: right;
    }

    .main-content h2 {
        font-size: 2rem;   /* smaller */
        font-weight: 200;  /* thinner */
        margin-bottom: 20px;
        color: #ffffff;
    }

    .main-content p {
        font-size: 1rem;   /* smaller */
        margin-bottom: 30px;
        max-width: 450px;
        color: #cfd8dc;
    }

    /* Buttons */
    .btn {
        padding: 12px 36px;
        border-radius: 50px; /* fully rounded ends */
        font-weight: 300;
        margin: 0 12px;
        transition: all 0.3s ease;
    }

    .btn-login {
        background-color: #ffffff;
        color: #000000;
        border: none;
    }

    .btn-login:hover {
        background-color: #f0f0f0;
    }

    .btn-signup {
        background-color: transparent;
        border: 2px solid #ffffff;
        color: #ffffff;
    }

    .btn-signup:hover {
        background-color: #ffffff;
        color: #001133;
    }
</style>
</head>
<body>

    <!-- Top bar -->
    <div class="top-bar">
        <h1>Centralized Event Promotion</h1>
        <nav>
            <a href="#">About</a>
            <a href="#">Service</a>
            <a href="#">Contact</a>
        </nav>
    </div>

    <!-- Layout -->
    <div class="layout">
        <!-- Left Event Element -->
        <div class="event-left">
            <img src="{{ asset('images/welcome.png') }}" alt="Event Organizer Icon">
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Find, Create and Promote Your Event Now</h2>
            <p>Discover and manage events at IIUM efficiently. Join your favourite events, create new ones, and promote them to the world!</p>
            <div>
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn btn-signup">Sign Up</a>
            </div>
        </div>
    </div>

</body>
</html>
