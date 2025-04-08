<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Privacy Policy</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/homepage-style.css') }}" />
  </head>
  <body>
    <header class="w-100 text-white py-3 Header">
      <nav class="container d-flex justify-content-between align-items-center">
        <a class="text-white text-decoration-none" href="index.html">
          <img src="{{ asset('assets/admin/images/logo.png') }}" alt="logo" />
        </a>
      </nav>
    </header>

    <main class="container my-5">
      <h1>Privacy Policy</h1>
      <p class="text-muted"><strong>Effective Date:</strong> 08/04/2025</p>
      <p>
        At Quicks, operated by Trendra Technologies Private Limited, we are
        committed to protecting your privacy. This policy explains how your
        information is collected, used, and safeguarded.
      </p>

      <h4>1. Information We Collect</h4>
      <strong>a. Personal Information:</strong>
      <ul>
        <li>Full name</li>
        <li>Email address</li>
        <li>Mobile number</li>
        <li>Bank or UPI details</li>
        <li>Address (if required)</li>
      </ul>

      <strong>b. Non-Personal Information:</strong>
      <ul>
        <li>Device and browser details</li>
        <li>IP address and location</li>
        <li>Usage stats and referral data</li>
      </ul>

      <strong>c. Cookies:</strong>
      <p>
        We use cookies and similar tools to track activity, enhance UX, and
        ensure affiliate tracking. You may control cookies via browser settings.
      </p>

      <h4>2. How We Use Your Information</h4>
      <ul>
        <li>Track transactions and apply cashback</li>
        <li>Process payments</li>
        <li>Send updates and offers</li>
        <li>Prevent fraud</li>
        <li>Improve platform experience</li>
      </ul>

      <h4>3. Sharing Your Information</h4>
      <p>
        Your data may be shared with affiliate merchants, payment processors, or
        legal authorities if required. We do not sell or rent personal data.
      </p>

      <h4>4. Data Security</h4>
      <p>
        We use standard security practices to protect your data, but no method
        is 100% secure. Always use strong passwords and keep credentials
        private.
      </p>

      <h4>5. Your Rights</h4>
      <ul>
        <li>Access and correct your data</li>
        <li>Request account/data deletion</li>
        <li>Opt out of marketing communication</li>
        <li>
          Email:
          <a href="mailto:support@quicks.money">support@quicks.money</a>
        </li>
      </ul>

      <h4>6. Children’s Privacy</h4>
      <p>
        Our platform is not for users under 13. If such data is collected, it
        will be deleted immediately.
      </p>

      <h4>7. Changes to This Policy</h4>
      <p>
        We may revise this policy occasionally. Continued use implies
        acceptance.
      </p>

      <h4>8. Contact Us</h4>
      <p>
        Email: <a href="mailto:support@quicks.money">support@quicks.money</a
        ><br />
        Address: Asansol, Chalbalpur, Bidhanbag, Barddhaman, 713337<br />
        Trendra Technologies Private Limited
      </p>
    </main>

    <footer class="footer text-white">
      <div class="container">
        <p class="mb-0">
          © 2025 Quicks. All rights reserved. |
          <a href="{{route('home.page',['page'=>'privacy-policy'])}}" class="text-white text-decoration-none"
            >Privacy Policy</a
          >
          |
          <a
            href="{{route('home.page',['page'=>'terms-conditions'])}}"
            class="text-white text-decoration-none"
            >Terms and Conditions</a
          >
          |
          <a href="{{route('home.page',['page'=>'contact'])}}" class="text-white text-decoration-none"
            >Contact Us</a
          >
        </p>
      </div>
    </footer>
  </body>
</html>
