document.addEventListener('DOMContentLoaded', function () {

  const menuBtn = document.querySelector('.menu-toggle');
  const body = document.body;
  const overlay = document.querySelector('.sidebar-overlay');

  if (menuBtn) {
    menuBtn.addEventListener('click', function () {
      body.classList.toggle('sidebar-open');
    });
  }

  if (overlay) {
    overlay.addEventListener('click', function () {
      body.classList.remove('sidebar-open');
    });
  }

});
