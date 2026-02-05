'use strict';

/* =====================================================
   DOM READY
===================================================== */
document.addEventListener('DOMContentLoaded', () => {

  /* =====================================================
     SAFE QUERY HELPER
  ===================================================== */
  const $ = (q) => document.querySelector(q);
  const $$ = (q) => document.querySelectorAll(q);

  const addEvent = (el, ev, fn) => el && el.addEventListener(ev, fn);

  /* =====================================================
     HEADER / NAVBAR
  ===================================================== */
  const navbar = $('[data-navbar]');
  const navToggler = $('[data-nav-toggler]');
  const navLinks = $$('[data-nav-link]');

  addEvent(navToggler, 'click', e => {
    e.stopPropagation();
    navbar?.classList.toggle('active');
    navToggler?.classList.toggle('active');
  });

  navLinks.forEach(l => addEvent(l, 'click', () => {
    navbar?.classList.remove('active');
    navToggler?.classList.remove('active');
  }));

  document.addEventListener('click', e => {
    if (navbar?.classList.contains('active') &&
        !navbar.contains(e.target) &&
        !navToggler?.contains(e.target)) {
      navbar.classList.remove('active');
      navToggler?.classList.remove('active');
    }
  });

  /* =====================================================
     HEADER SCROLL EFFECT
  ===================================================== */
  const header = $('[data-header]');
  const backTop = $('[data-back-top-btn]');

  window.addEventListener('scroll', () => {
    const active = window.scrollY > 100;
    header?.classList.toggle('active', active);
    backTop?.classList.toggle('active', active);
  });

  /* =====================================================
     SCROLL FADE-IN
  ===================================================== */
  const animated = $$('.animate');
  if (animated.length) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => e.isIntersecting && e.target.classList.add('show'));
    }, { threshold: 0.2 });
    animated.forEach(el => io.observe(el));
  }

  /* =====================================================
     SIDEBAR (CATEGORY FILTER)
  ===================================================== */
  const sidebar = $('.ae-sidebar');
  const sidebarToggle = $('.ae-sidebar-toggle');
  const sidebarOverlay = $('.ae-sidebar-overlay');

  addEvent(sidebarToggle, 'click', () => {
    sidebar?.classList.add('active');
    sidebarOverlay?.classList.add('active');
  });

  addEvent(sidebarOverlay, 'click', () => {
    sidebar?.classList.remove('active');
    sidebarOverlay?.classList.remove('active');
  });

  $$('.ae-toggle').forEach(toggle => {
    addEvent(toggle, 'click', e => {
      e.preventDefault();
      const parent = toggle.closest('.ae-item');
      $$('.ae-item').forEach(i => i !== parent && i.classList.remove('active'));
      parent?.classList.toggle('active');
    });
  });

  /* =====================================================
     SLIDE CART
  ===================================================== */
  const cartIcon = $('.nav-cart a');
  const cartPanel = $('.ae-cart-panel');
  const cartOverlay = $('.ae-cart-overlay');
  const cartClose = $('.ae-cart-close');

  addEvent(cartIcon, 'click', e => {
    e.preventDefault();
    cartPanel?.classList.add('active');
    cartOverlay?.classList.add('active');
    if (window.innerWidth <= 992) document.body.style.overflow = 'hidden';
    loadMiniCart();
  });

  const closeCart = () => {
    cartPanel?.classList.remove('active');
    cartOverlay?.classList.remove('active');
    document.body.style.overflow = '';
  };

  addEvent(cartClose, 'click', closeCart);
  addEvent(cartOverlay, 'click', closeCart);

  function loadMiniCart() {
    fetch('actions/cart-ajax.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'action=fetch'
    })
    .then(r => r.json())
    .then(data => {
      const box = $('#miniCartItems');
      const totalEl = $('#miniCartTotal');
      const waBtn = $('#miniCartWhatsapp');
      if (!box) return;

      box.innerHTML = '';
      let total = 0;
      let waText = 'Hello Ashoka EV,%0A%0AMy cart items:%0A';

      data.cart.forEach(i => {
        total += i.price * i.qty;
        box.innerHTML += `
          <div class="mini-cart-item">
            <img src="${i.image}">
            <div class="mini-cart-info">
              <h6>${i.name}</h6>
              <small>${i.qty} × ₹${i.price}</small>
            </div>
          </div>`;
        waText += `• ${i.name} (${i.qty})%0A`;
      });

      totalEl && (totalEl.textContent = total);
      waBtn && (waBtn.href = `https://wa.me/919431492953?text=${waText}%0ATotal: ₹${total}`);
    });
  }

  /* =====================================================
     OTP PAGE LOGIC (SAFE – ONLY RUNS ON OTP PAGE)
  ===================================================== */
  const otpInput = $('.otp-input');
  const resendBtn = $('#resendBtn');
  const timerEl = $('#otpTimer');
  const errorBox = $('.otp-error');

  /* Auto-focus + numeric keyboard */
  if (otpInput) {
    otpInput.focus();
    otpInput.addEventListener('input', () => {
      errorBox?.classList.remove('shake');
    });
  }

  /* Resend OTP Countdown */
  if (resendBtn && timerEl) {
    let timeLeft = 60;
    resendBtn.disabled = true;

    const startTimer = () => {
      timerEl.textContent = `Resend available in ${timeLeft}s`;
      const t = setInterval(() => {
        timeLeft--;
        timerEl.textContent = `Resend available in ${timeLeft}s`;
        if (timeLeft <= 0) {
          clearInterval(t);
          timerEl.textContent = "Didn't receive the code?";
          resendBtn.disabled = false;
        }
      }, 1000);
    };

    startTimer();

    addEvent(resendBtn, 'click', () => {
      resendBtn.disabled = true;
      timerEl.textContent = "Sending new OTP...";

      fetch('resend-otp.php')
        .then(r => r.json())
        .then(d => {
          if (d.success) {
            timeLeft = 60;
            startTimer();
          } else {
            timerEl.textContent = "Failed to resend OTP";
            resendBtn.disabled = false;
          }
        });
    });
  }

});
