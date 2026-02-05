<?php include 'includes/header.php'; ?>

<div class="cf-contact">
  <div class="cf-wrapper">

    <!-- LEFT INFO PANEL -->
    <div class="cf-info">
      <div class="cf-info-item">
        <i class="fa-solid fa-location-dot"></i>
        <h4>Address</h4>
        <p>14B/108, East Ashok Nagar, Kankarbagh, Patna - 800020</p>
      </div>

      <div class="cf-info-item">
        <i class="fa-solid fa-phone"></i>
        <h4>Let's Talk</h4>
        <p>+91 9431492953</p>
      </div>

      <div class="cf-info-item">
        <i class="fa-solid fa-envelope"></i>
        <h4>General Support</h4>
        <p>contact@example.com</p>
      </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="cf-form">
      <h2>Send Us A Message</h2>

      <form>
        <div class="cf-group cf-name-group">
          <label>Your Name *</label>
          <input type="text" placeholder="First name" required>
          <input type="text" placeholder="Last name" required>
        </div>

        <div class="cf-group">
          <label>Email *</label>
          <input type="email" placeholder="example@email.com" required>
        </div>

        <div class="cf-group">
          <label>Phone *</label>
          <input type="text" placeholder="+1 800 000000" required>
        </div>

        <div class="cf-group">
          <label>Message</label>
          <textarea placeholder="Write us a message"></textarea>
        </div>

        <button type="submit" class="cf-btn">Send Message</button>
      </form>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
