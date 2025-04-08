<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/homepage-style.css') }}" />
    <style>
      @media (min-width: 768px) {
        footer {
          position: fixed;
          bottom: 0;
          width: 100%;
        }
      }
    </style>
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
      <h1 class="mb-4">Contact Us</h1>
      <div class="info-box">
        <p>
          We’re here to help! Whether you have questions, feedback, or need
          support with your account or cashback, feel free to reach out to us:
        </p>

        <h4 class="mt-4">Customer Support Email</h4>
        <p>
          <a href="mailto:support@quicks.money">support@quicks.money</a>
        </p>

        <h4 class="mt-4">Mail Support</h4>
        <p>Available Monday to Saturday, 10:00 AM – 6:00 PM IST</p>

        <h4 class="mt-4">Office Address</h4>
        <p>
          Trendra Technologies Private Limited<br />
          #Asansol, Chalbalpur, Bidhanbag, 713337<br />
          India
        </p>

        <h4 class="mt-4">Business Hours</h4>
        <p>
          Monday to Saturday: 10:00 AM – 6:00 PM<br />
          Sunday: Closed
        </p>

        <h4 class="mt-4">Need Quick Help?</h4>
        <p>
          For faster support, you can also use the in-app chat feature or visit
          our Help Center within the Quicks app.
        </p>
      </div>
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
