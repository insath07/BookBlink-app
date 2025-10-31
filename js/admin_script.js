let navbar = document.querySelector('.header .navbar');
let accountBox = document.querySelector('.header .account-box');

// Menu toggle
document.querySelector('#menu-btn')?.addEventListener('click', () => {
   navbar.classList.toggle('active');
   accountBox.classList.remove('active');
});

// User/account toggle
document.querySelector('#user-btn')?.addEventListener('click', () => {
   accountBox.classList.toggle('active');
   navbar.classList.remove('active');
});

// On scroll: hide all dropdowns
window.addEventListener('scroll', () => {
   navbar.classList.remove('active');
   accountBox.classList.remove('active');
});

// Close update form (only if element exists)
document.querySelector('#close-update')?.addEventListener('click', () => {
   const editForm = document.querySelector('.edit-product-form');
   if (editForm) {
      editForm.style.display = 'none';
      window.location.href = 'admin_products.php';
   }
});
