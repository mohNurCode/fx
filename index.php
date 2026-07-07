<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mafuya Solution · Forex Exchange</title>
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background: #f6f9fc;
      color: #0b1a2e;
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    /* ----- HEADER / NAV (exactly as described) ----- */
    .navbar {
      background: #0b1a2e;
      color: #fff;
      padding: 16px 0;
      border-bottom: 2px solid #f5b342;
    }

    .navbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo-area {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-icon {
      background: #f5b342;
      color: #0b1a2e;
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      font-weight: 700;
    }

    .logo-text {
      font-size: 1.7rem;
      font-weight: 700;
      letter-spacing: -0.5px;
    }

    .logo-text span {
      color: #f5b342;
    }

    .nav-links {
      display: flex;
      gap: 28px;
      font-weight: 500;
      flex-wrap: wrap;
    }

    .nav-links a {
      color: #eef2f6;
      text-decoration: none;
      font-size: 0.95rem;
      transition: 0.2s;
      border-bottom: 2px solid transparent;
      padding-bottom: 4px;
    }

    .nav-links a:hover {
      color: #f5b342;
      border-bottom-color: #f5b342;
    }

    .hamburger {
      display: none;
      font-size: 1.8rem;
      cursor: pointer;
      color: #fff;
    }

    /* ----- HERO ----- */
    .hero {
      background: linear-gradient(135deg, #0b1a2e 0%, #1f3a57 100%);
      color: white;
      padding: 60px 0 70px;
      margin-bottom: 40px;
      border-radius: 0 0 48px 48px;
    }

    .hero .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 16px;
    }

    .hero h1 span {
      color: #f5b342;
    }

    .hero p {
      font-size: 1.2rem;
      max-width: 640px;
      opacity: 0.9;
      margin-bottom: 28px;
    }

    .hero .btn-hero {
      background: #f5b342;
      border: none;
      padding: 14px 44px;
      border-radius: 60px;
      font-weight: 700;
      font-size: 1rem;
      color: #0b1a2e;
      cursor: pointer;
      transition: 0.2s;
      box-shadow: 0 6px 14px rgba(245, 179, 66, 0.3);
      text-decoration: none;
      display: inline-block;
    }

    .hero .btn-hero:hover {
      background: #e6a33a;
      transform: scale(1.02);
    }

    /* ----- RATES + CALCULATOR (two columns) ----- */
    .rates-section {
      display: grid;
      grid-template-columns: 1.8fr 1.2fr;
      gap: 30px;
      margin: 20px 0 50px;
    }

    .card {
      background: white;
      border-radius: 28px;
      padding: 28px 30px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
      border: 1px solid #e9edf2;
    }

    .card h3 {
      font-size: 1.4rem;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .card h3 i {
      color: #f5b342;
    }

    .rate-grid {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .rate-item {
      display: flex;
      justify-content: space-between;
      padding: 12px 6px;
      border-bottom: 1px solid #eef2f7;
      font-weight: 500;
    }

    .rate-item:last-child {
      border-bottom: none;
    }

    .rate-currency i {
      width: 28px;
      color: #1f3a57;
    }

    .rate-buy { color: #0f7b3a; font-weight: 600; }
    .rate-sell { color: #b13e3e; font-weight: 600; }

    .calc-group {
      margin: 16px 0 12px;
    }

    .calc-group label {
      font-weight: 500;
      font-size: 0.9rem;
      display: block;
      margin-bottom: 6px;
      color: #1f3a57;
    }

    .calc-group input, .calc-group select {
      width: 100%;
      padding: 14px 16px;
      border-radius: 18px;
      border: 1px solid #dce2ea;
      font-size: 1rem;
      background: #fafcfe;
    }

    .calc-group input:focus, .calc-group select:focus {
      border-color: #f5b342;
      outline: none;
      box-shadow: 0 0 0 3px rgba(245, 179, 66, 0.15);
    }

    .calc-result {
      background: #f0f5fb;
      padding: 18px 16px;
      border-radius: 20px;
      margin: 20px 0 12px;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
    }

    .btn {
      background: #f5b342;
      border: none;
      color: #0b1a2e;
      font-weight: 600;
      padding: 16px 28px;
      border-radius: 40px;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.2s;
      width: 100%;
    }

    .btn:hover { background: #e6a33a; }

    .btn-outline {
      background: transparent;
      border: 1.5px solid #dce2ea;
      color: #1f3a57;
      margin-top: 8px;
    }
    .btn-outline:hover { background: #f0f5fb; }

    /* ----- ABOUT + MISSION / VALUES (two columns) ----- */
    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      margin: 50px 0;
    }

    .about-text h2 {
      font-size: 2rem;
      margin-bottom: 16px;
    }
    .about-text h2 span { color: #f5b342; }

    .values-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-top: 20px;
      list-style: none;
    }
    .values-list li {
      display: flex;
      align-items: center;
      gap: 14px;
      font-weight: 500;
    }
    .values-list i { color: #f5b342; width: 26px; }

    .reg-badge {
      background: #eef4fa;
      padding: 24px 26px;
      border-radius: 28px;
      border-left: 6px solid #f5b342;
    }
    .reg-badge .nbe-link {
      display: inline-block;
      margin-top: 16px;
      background: #0b1a2e;
      color: white;
      padding: 10px 28px;
      border-radius: 60px;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
    }

    /* ----- SERVICES (3 columns) ----- */
    .services {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
      margin: 40px 0 50px;
    }
    .service-item {
      background: white;
      border-radius: 28px;
      padding: 28px 22px;
      text-align: center;
      border: 1px solid #eef2f7;
      transition: 0.2s;
    }
    .service-item:hover { transform: translateY(-6px); box-shadow: 0 12px 28px rgba(0,0,0,0.03); }
    .service-item i { font-size: 2.6rem; color: #f5b342; margin-bottom: 16px; }

    /* ----- BRANCHES (3 columns) ----- */
    .branches {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
      margin: 40px 0 50px;
    }
    .branch-card {
      background: white;
      border-radius: 24px;
      padding: 24px 22px;
      border: 1px solid #eef2f7;
    }
    .branch-card i { color: #f5b342; margin-right: 8px; width: 24px; }
    .coming { background: #fff0ee; color: #b13e3e; padding: 2px 14px; border-radius: 60px; font-size: 0.8rem; font-weight: 500; }

    /* ----- EXCHANGE RATES CTA ----- */
    .rates-cta {
      background: #0b1a2e;
      color: white;
      border-radius: 40px;
      padding: 32px 36px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin: 30px 0 40px;
    }
    .rates-cta a {
      background: #f5b342;
      color: #0b1a2e;
      padding: 14px 38px;
      border-radius: 60px;
      font-weight: 700;
      text-decoration: none;
    }

    /* ----- CONTACT (two columns) ----- */
    .contact-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      background: white;
      border-radius: 32px;
      padding: 40px 36px;
      margin: 30px 0 60px;
      border: 1px solid #eef2f7;
    }
    .contact-info p { margin: 16px 0; display: flex; align-items: center; gap: 14px; }
    .contact-info i { width: 28px; color: #f5b342; }

    .contact-form input, .contact-form textarea {
      width: 100%;
      padding: 14px 18px;
      border-radius: 20px;
      border: 1px solid #dce2ea;
      margin-bottom: 16px;
      font-size: 1rem;
      background: #fafcfe;
    }
    .contact-form textarea { height: 100px; resize: vertical; }
    .contact-form button {
      background: #f5b342;
      border: none;
      padding: 16px;
      border-radius: 60px;
      font-weight: 700;
      font-size: 1rem;
      width: 100%;
      color: #0b1a2e;
      cursor: pointer;
    }

    /* ----- FOOTER (exactly as described) ----- */
    .footer {
      background: #0b1a2e;
      color: #bcc9db;
      padding: 36px 0 28px;
      border-radius: 40px 40px 0 0;
    }
    .footer .container {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1.5fr;
      gap: 30px;
    }
    .footer h4 { color: white; margin-bottom: 14px; font-weight: 600; }
    .footer a { color: #bcc9db; text-decoration: none; display: block; margin: 6px 0; }
    .footer a:hover { color: #f5b342; }
    .footer .social i { font-size: 1.4rem; margin-right: 12px; color: #bcc9db; }
    .footer .social i:hover { color: #f5b342; }
    .footer-bottom {
      grid-column: 1 / -1;
      border-top: 1px solid #1f3a57;
      padding-top: 20px;
      margin-top: 10px;
      text-align: center;
      font-size: 0.9rem;
    }
    .footer-bottom span { color: #f5b342; }

    /* ----- responsive ----- */
    @media (max-width: 920px) {
      .rates-section, .about-grid, .contact-wrap, .footer .container {
        grid-template-columns: 1fr;
      }
      .services, .branches {
        grid-template-columns: 1fr 1fr;
      }
    }
    @media (max-width: 640px) {
      .nav-links {
        display: none;
        width: 100%;
        flex-direction: column;
        align-items: center;
        padding: 16px 0 8px;
        gap: 14px;
      }
      .nav-links.show { display: flex; }
      .hamburger { display: block; }
      .hero h1 { font-size: 2.2rem; }
      .services, .branches { grid-template-columns: 1fr; }
      .rates-cta { flex-direction: column; gap: 18px; text-align: center; }
    }
  </style>
</head>
<body>
  <!-- ========== NAVIGATION ========== -->
  <nav class="navbar">
    <div class="container">
      <div class="logo-area">
        <div class="logo-icon">M</div>
        <div class="logo-text">Mafuya<span>Solution</span></div>
      </div>
      <div class="hamburger" id="hamburger"><i class="fas fa-bars"></i></div>
      <div class="nav-links" id="navLinks">
        <a href="#">Home</a>
        <a href="#about">About</a>
        <a href="#laws">Laws</a>
        <a href="#services">Services</a>
        <a href="#branches">Branches</a>
        <a href="#calculator">ForexCalculator</a>
        <a href="#contact">Contact</a>
        <a href="#"><i class="fas fa-chart-line"></i> Forex Trading</a>
      </div>
    </div>
  </nav>

  <!-- ========== HERO ========== -->
  <section class="hero">
    <div class="container">
      <h1>Your Trusted Partner in <span>Foreign Exchange</span></h1>
      <p>Experience seamless currency exchange with competitive rates and professional service.</p>
      <a href="#rates" class="btn-hero"><i class="fas fa-arrow-right"></i> Check Exchange Rates</a>
    </div>
  </section>

  <!-- ========== LIVE RATES + CALCULATOR ========== -->
  <div class="container" id="rates">
    <div class="rates-section">
      <!-- Live Rates -->
      <div class="card">
        <h3><i class="fas fa-chart-line"></i> Live Exchange Rates</h3>
        <div class="rate-grid" id="rateGrid">
          <!-- JS will populate -->
        </div>
        <div style="margin-top: 16px; font-size: 0.85rem; color: #5f748b; background: #f4f8fe; padding: 6px 16px; border-radius: 60px; display: inline-block;">
          <i class="fas fa-sync-alt"></i> rates updated every 60s
        </div>
      </div>

      <!-- Calculator -->
      <div class="card" id="calculator">
        <h3><i class="fas fa-calculator"></i> Forex Calculator</h3>
        <div class="calc-group">
          <label for="amountInput">Amount (ETB)</label>
          <input type="number" id="amountInput" value="10000" step="100" min="0">
        </div>
        <div class="calc-group">
          <label for="currencySelect">Target Currency</label>
          <select id="currencySelect">
            <option value="USD">USD – US Dollar</option>
            <option value="EUR">EUR – Euro</option>
            <option value="GBP">GBP – British Pound</option>
          </select>
        </div>
        <div class="calc-result">
          <span>You get</span>
          <span id="calcResult">—</span>
        </div>
        <button class="btn" id="calcBtn"><i class="fas fa-arrow-right"></i> Calculate</button>
        <button class="btn btn-outline" id="resetCalcBtn"><i class="fas fa-undo-alt"></i> Reset</button>
      </div>
    </div>
  </div>

  <!-- ========== ABOUT + MISSION / VALUES ========== -->
  <div class="container" id="about">
    <div class="about-grid">
      <div class="about-text">
        <h2>About <span>Mafuya Solution</span></h2>
        <h4 style="margin-top: 6px; color: #1f3a57;">About Us</h4>
        <p style="margin: 12px 0 6px;"><strong>Our Mission</strong><br>At Mafuya Solution, we strive to provide reliable and efficient foreign exchange services to our valued customers. With years of experience, we ensure competitive rates and exceptional service.</p>
        <ul class="values-list">
          <li><i class="fas fa-check-circle"></i> Transparency in all transactions</li>
          <li><i class="fas fa-globe"></i> Global standards and compliance</li>
          <li><i class="fas fa-user-tie"></i> Professional expertise</li>
        </ul>
      </div>
      <div class="reg-badge" id="laws">
        <h4 style="display: flex; gap: 10px; margin-bottom: 10px;"><i class="fas fa-gavel" style="color: #f5b342;"></i> Forex Laws & Regulations</h4>
        <p>We operate in full compliance with the National Bank of Ethiopia's forex regulations. Stay informed about the latest forex laws and guidelines governing currency exchange in Ethiopia.</p>
        <a href="#" class="nbe-link"><i class="fas fa-external-link-alt"></i> Visit NBE Website</a>
      </div>
    </div>
  </div>

  <!-- ========== SERVICES ========== -->
  <div class="container" id="services">
    <h2 style="font-size: 2rem; margin-top: 20px;">Our <span style="color: #f5b342;">Services</span></h2>
    <div class="services">
      <div class="service-item"><i class="fas fa-exchange-alt"></i><h4>Currency Exchange</h4><p>Exchange major world currencies at competitive rates</p></div>
      <div class="service-item"><i class="fas fa-plane"></i><h4>Travel Currency</h4><p>Special rates for travelers and businesses</p></div>
      <div class="service-item"><i class="fas fa-building"></i><h4>Corporate FX</h4><p>Tailored solutions for companies</p></div>
    </div>
  </div>

  <!-- ========== BRANCHES ========== -->
  <div class="container" id="branches">
    <h2 style="font-size: 2rem; margin-top: 20px;">Our <span style="color: #f5b342;">Branches</span></h2>
    <div class="branches">
      <div class="branch-card"><i class="fas fa-map-pin"></i> <strong>Beherawi Branch</strong><br>Bole Road, Addis Ababa<br><i class="fas fa-phone"></i> +251 90 825 1111</div>
      <div class="branch-card"><i class="fas fa-map-pin"></i> <strong>Merkato</strong> <span class="coming">Coming Soon</span><br>Addis Ababa<br><i class="fas fa-phone"></i> +251 90 825 1111</div>
      <div class="branch-card"><i class="fas fa-map-pin"></i> <strong>Piassa</strong> <span class="coming">Coming Soon</span><br>Addis Ababa<br><i class="fas fa-phone"></i> +251 90 825 1111</div>
    </div>
  </div>

  <!-- ========== EXCHANGE RATES CTA ========== -->
  <div class="container">
    <div class="rates-cta">
      <div><strong style="font-size: 1.2rem;">Exchange Rates</strong><br>For real-time exchange rates, please visit our forex bureau system:</div>
      <a href="#"><i class="fas fa-arrow-right"></i> Check Live Rates</a>
    </div>
  </div>

  <!-- ========== CONTACT ========== -->
  <div class="container" id="contact">
    <div class="contact-wrap">
      <div class="contact-info">
        <h3 style="font-size: 1.8rem; margin-bottom: 16px;"><i class="fas fa-headset" style="color: #f5b342;"></i> Contact Us</h3>
        <p><i class="fas fa-phone-alt"></i> +251 90 825 1111</p>
        <p><i class="fas fa-envelope"></i> info@mafuyasolution.com</p>
        <p><i class="fas fa-location-dot"></i> Bole Road, Addis Ababa, Ethiopia (in front of Beherawi Theater)</p>
        <div style="margin-top: 28px; display: flex; gap: 20px; font-size: 1.8rem;">
          <i class="fab fa-telegram"></i> <i class="fab fa-whatsapp"></i> <i class="fab fa-facebook"></i>
        </div>
      </div>
      <div class="contact-form">
        <input type="text" placeholder="Name" value="Your name">
        <input type="email" placeholder="Email" value="Your email">
        <textarea placeholder="Message">Your message</textarea>
        <button><i class="fas fa-paper-plane"></i> Send Message</button>
      </div>
    </div>
  </div>

  <!-- ========== FOOTER (exact structure) ========== -->
  <footer class="footer">
    <div class="container">
      <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
          <div class="logo-icon" style="background: #f5b342; color: #0b1a2e; width: 40px; height: 40px; font-size: 1.2rem;">M</div>
          <span style="color: white; font-weight: 700; font-size: 1.3rem;">Mafuya<span style="color: #f5b342;">Solution</span></span>
        </div>
        <p style="font-size: 0.9rem;">Your trusted partner for all foreign exchange needs.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <a href="#">Home</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#rates">Exchange Rates</a>
        <a href="#contact">Contact</a>
      </div>
      <div>
        <h4>Business Hours</h4>
        <p>Monday - Friday: 8:30 AM - 5:00 PM</p>
        <p>Saturday: 9:00 AM - 1:00 PM</p>
        <p>Sunday: Closed</p>
      </div>
      <div>
        <h4>Follow Us</h4>
        <div class="social">
          <i class="fab fa-facebook"></i>
          <i class="fab fa-twitter"></i>
          <i class="fab fa-instagram"></i>
          <i class="fab fa-linkedin"></i>
        </div>
      </div>
      <div class="footer-bottom">
        © 2026 Mafuya Solution. Developed by <span>Gudalabs</span>.
      </div>
    </div>
  </footer>

  <!-- ========== JAVASCRIPT ========== -->
  <script>
    (function() {
      // ---------- RATES ----------
      const rateData = {
        USD: { buy: 56.85, sell: 57.70 },
        EUR: { buy: 61.20, sell: 62.10 },
        GBP: { buy: 71.40, sell: 72.55 }
      };

      const rateGrid = document.getElementById('rateGrid');

      function renderRates() {
        let html = '';
        for (const [code, rates] of Object.entries(rateData)) {
          html += `
            <div class="rate-item">
              <span class="rate-currency"><i class="fas fa-money-bill-wave"></i> ${code}</span>
              <span><span class="rate-buy">Buy ${rates.buy.toFixed(2)}</span>  <span class="rate-sell">Sell ${rates.sell.toFixed(2)}</span></span>
            </div>
          `;
        }
        rateGrid.innerHTML = html;
      }
      renderRates();

      // ---------- CALCULATOR ----------
      const amountInput = document.getElementById('amountInput');
      const currencySelect = document.getElementById('currencySelect');
      const calcResult = document.getElementById('calcResult');
      const calcBtn = document.getElementById('calcBtn');
      const resetBtn = document.getElementById('resetCalcBtn');

      function calculate() {
        const amount = parseFloat(amountInput.value);
        if (isNaN(amount) || amount < 0) {
          calcResult.textContent = 'Enter valid amount';
          return;
        }
        const currency = currencySelect.value;
        const rate = rateData[currency];
        if (!rate) { calcResult.textContent = 'Rate unavailable'; return; }
        const foreign = amount / rate.sell;
        calcResult.textContent = `${foreign.toFixed(2)} ${currency}`;
      }

      calcBtn.addEventListener('click', calculate);
      resetBtn.addEventListener('click', function() {
        amountInput.value = '10000';
        currencySelect.value = 'USD';
        calcResult.textContent = '—';
      });
      amountInput.addEventListener('input', calculate);
      currencySelect.addEventListener('change', calculate);
      setTimeout(calculate, 100);

      // ---------- HAMBURGER ----------
      const hamburger = document.getElementById('hamburger');
      const navLinks = document.getElementById('navLinks');
      hamburger.addEventListener('click', (e) => {
        e.stopPropagation();
        navLinks.classList.toggle('show');
      });
      navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => navLinks.classList.remove('show'));
      });
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.navbar')) navLinks.classList.remove('show');
      });

      // ---------- RATE SIMULATION (refresh) ----------
      setInterval(() => {
        for (let key of Object.keys(rateData)) {
          const shift = (Math.random() * 0.3) - 0.15;
          rateData[key].buy = Math.max(20, rateData[key].buy + shift);
          rateData[key].sell = Math.max(20, rateData[key].sell + shift);
        }
        renderRates();
        calculate();
      }, 30000);

      // smooth anchor scroll
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          const targetId = this.getAttribute('href');
          if (targetId === '#') return;
          const target = document.querySelector(targetId);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });
    })();
  </script>
</body>
</html>