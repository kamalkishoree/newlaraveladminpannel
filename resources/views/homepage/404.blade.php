<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Quicks</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Mada:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<style>
/* Font and Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Mada', sans-serif;
    background-color: #fbfbfb;
    color: #251e1a;
    line-height: 1.5;
}

.main-wrapper {
    display: flex;
    min-height: 100vh;
    justify-content: center;
    align-items: center;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Error Box Styles */
.error-box {
    display: flex;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.error-left {
    background-color: #dba108;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px;
    width: 40%;
}

.logo {
    max-width: 200px;
    margin-bottom: 20px;
}

.logo img {
    width: 100%;
    height: auto;
}

.error-right {
    width: 60%;
    padding: 40px;
}

.error-right-wrap {
    max-width: 400px;
    margin: 0 auto;
}

h1 {
    font-size: 120px;
    font-weight: 700;
    color: #dba108;
    margin-bottom: 10px;
    line-height: 1;
}

h2 {
    font-size: 30px;
    font-weight: 600;
    color: #251e1a;
    margin-bottom: 20px;
}

.error-subtitle {
    font-size: 16px;
    color: #9a9b9b;
    margin-bottom: 30px;
}

.error-actions {
    margin-bottom: 30px;
}

.btn {
    display: inline-block;
    padding: 12px 30px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #dba108;
    color: #ffffff;
}

.btn-primary:hover {
    background-color: #896a21;
}

.footer-links {
    margin-top: 30px;
    text-align: center;
    font-size: 14px;
}

.footer-links a {
    color: #9a9b9b;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: #dba108;
}

footer {
    text-align: center;
    margin-top: 30px;
    color: #9a9b9b;
    font-size: 14px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .error-box {
        flex-direction: column;
    }

    .error-left, .error-right {
        width: 100%;
    }

    .error-left {
        padding: 30px;
    }

    h1 {
        font-size: 80px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 60px;
    }

    h2 {
        font-size: 24px;
    }

    .error-subtitle {
        font-size: 14px;
    }

    .btn {
        padding: 10px 20px;
        font-size: 14px;
    }
}

</style>



    <div class="main-wrapper">
        <div class="container">
            <div class="error-box">
                <div class="error-left">
                    <div class="logo">
                        <img src="https://ext.same-assets.com/2371730725/3718733537.svg" alt="Quicks Logo">
                    </div>
                </div>
                <div class="error-right">
                    <div class="error-right-wrap">
                        <h1>404</h1>
                        <h2>Oops! Page Not Found</h2>
                        <p class="error-subtitle">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                        <div class="error-actions">
                            <a href="/" class="btn btn-primary">Back to Home</a>
                        </div>
                        <div class="footer-links">
                            <a href="/page/privacy-policy">Privacy Policy</a> |
                            <a href="/page/terms-conditions">Terms and Conditions</a> |
                            <a href="/page/contact">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-muted">
                <p>© <span id="current-year">2025</span> Quicks. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script>
        // Update copyright year
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
