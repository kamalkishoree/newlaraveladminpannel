<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coming Soon | Something Amazing is Coming</title>
    <meta
      name="description"
      content="We're working on something incredible. Stay tuned for our launch."
    />
    <meta name="author" content="Quicks" />
    <meta
      property="og:image"
      content="https://lovable.dev/opengraph-image-p98pqg.png"
    />
    <meta
      property="og:title"
      content="Coming Soon | Something Amazing is Coming"
    />
    <meta
      property="og:description"
      content="We're working on something incredible. Stay tuned for our launch."
    />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Custom Styles -->
    <style>
      :root {
        --text-color: #fff;
        --muted-text: #6c757d;
        --bg-color: #f8f9fa;
        --border-color: #dee2e6;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
          Helvetica, Arial, sans-serif;
        background-color: #000000d6;
        color: var(--text-color);
        overflow-x: hidden;
        background-image: url("/images/banner.jpg");
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        background-blend-mode: multiply;
      }

      .text-gradient {
        color: #fdc100;
      }

      .glass-panel {
        backdrop-filter: blur(10px);
        background-color: rgb(255 255 255 / 21%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) inset;
        border-radius: 0.5rem;
        transition: transform 0.3s;
      }

      .social-icon {
        transition: all 0.3s;
        position: relative;
        color: var(--text-color);
      }

      .social-icon:hover {
        color: var(--text-color);
      }

      .social-icon .tooltip {
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        background-color: var(--bg-color);
        color: var(--muted-text);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        pointer-events: none;
        white-space: nowrap;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }

      .social-icon:hover .tooltip {
        opacity: 1;
      }

      .coming-soon-chip {
        background-color: rgb(255 255 255 / 21%);
        color: var(--text-color);
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .fade-in {
        opacity: 0;
        animation: fadeIn 0.8s ease-out forwards;
      }

      .delay-1 {
        animation-delay: 0.2s;
      }
      .delay-2 {
        animation-delay: 0.4s;
      }
      .delay-3 {
        animation-delay: 0.6s;
      }
      .delay-4 {
        animation-delay: 0.8s;
      }
      .delay-5 {
        animation-delay: 1s;
      }
      .delay-6 {
        animation-delay: 1.2s;
      }
      .delay-7 {
        animation-delay: 1.4s;
      }

      .Logo_bigger {
        max-width: 370px;
        margin: 0 auto 30px;
      }

      .Logo_bigger img {
        width: 100%;
      }

      .FooterSection {
        padding-top: 50px;
        border-top: 1px solid #ccc;
        color: var(--text-color) !important;
      }

      .FooterSection a {
        color: var(--text-color) !important;
        text-decoration: none;
      }

      .wrapper {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 90vh;
      }
    </style>
  </head>

  <body>
    <div
      class="container-fluid min-vh-100 d-flex flex-column justify-content-center align-items-center position-relative"
    >
      <div class="container position-relative z-3 py-5">
        <div class="row justify-content-center">
          <div class="col-lg-12 text-center wrapper">
            <!-- Chip -->
            <div class="mb-4 fade-in delay-1">
              <div class="coming-soon-chip">Coming Soon</div>
            </div>

            <!-- Logo -->
            <div class="container position-relative z-3 fade-in delay-1">
              <div class="row text-center">
                <div class="Logo_bigger">
                  <img src="{{ asset('assets/admin/images/logo.png') }}" alt="logo" />
                </div>
              </div>
            </div>

            <div>
              <!-- Main Heading -->
              <h1 class="display-5 fw-bold mb-4 fade-in delay-2">
                <span class="text-gradient"
                  >Unlock Earnings with Every Click!<br />
                  Your Affiliate Empire Starts Soon!</span
                >
              </h1>

              <!-- Subheading -->
              <p
                class="lead text-white mb-5 mx-auto fade-in delay-3"
                style="max-width: 900px; font-weight: 400"
              >
                We're building a powerful platform to help you monetize your
                influence like never before. From high-converting offers to
                real-time tracking and generous commissions, our affiliate
                marketing hub will give you all the tools you need to succeed.
              </p>
            </div>

            <!-- Social Icons -->
            <div class="mb-5 fade-in delay-6">
              <div class="d-flex justify-content-center gap-4">
                <a
                  href="https://twitter.com"
                  target="_blank"
                  class="social-icon fs-4"
                  aria-label="Twitter"
                >
                  <!-- Twitter Icon SVG -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    stroke="currentColor"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"
                    />
                  </svg>
                  <span class="tooltip">Twitter</span>
                </a>
                <a
                  href="https://instagram.com"
                  target="_blank"
                  class="social-icon fs-4"
                  aria-label="Instagram"
                >
                  <!-- Instagram Icon SVG -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    stroke="currentColor"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                  </svg>
                  <span class="tooltip">Instagram</span>
                </a>
                <a
                  href="https://github.com"
                  target="_blank"
                  class="social-icon fs-4"
                  aria-label="GitHub"
                >
                  <!-- GitHub Icon SVG -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    stroke="currentColor"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"
                    />
                  </svg>
                  <span class="tooltip">GitHub</span>
                </a>
                <a
                  href="https://linkedin.com"
                  target="_blank"
                  class="social-icon fs-4"
                  aria-label="LinkedIn"
                >
                  <!-- LinkedIn Icon SVG -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    stroke="currentColor"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"
                    />
                    <rect x="2" y="9" width="4" height="12"></rect>
                    <circle cx="4" cy="4" r="2"></circle>
                  </svg>
                  <span class="tooltip">LinkedIn</span>
                </a>
              </div>
            </div>

            <!-- Footer -->
            <footer class="text-muted small fade-in delay-7 FooterSection">
              <div class="row">
                <div class="col-md-6 text-center text-md-start">
                  <p>
                    © <span id="current-year"></span> Quicks. All rights
                    reserved.
                  </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                  <a href="{{route('home.page',['page'=>'privacy-policy'])}}">Privacy Policy</a> |
                  <a href="{{route('home.page',['page'=>'terms-conditions'])}}">Terms and Conditions</a> |
                  <a href="{{route('home.page',['page'=>'contact'])}}">Contact Us</a>
                </div>
              </div>
            </footer>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Auto update year
      document.getElementById("current-year").textContent =
        new Date().getFullYear();
    </script>
  </body>
</html>
