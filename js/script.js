let userBox = document.querySelector('.header .header-2 .user-box');
let navbar = document.querySelector('.header .header-2 .navbar'); // Move this above

// Toggle user box
document.querySelector('#user-btn').onclick = () => {
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}

// Toggle navbar
document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

// On scroll hide both
window.onscroll = () => {
   userBox.classList.remove('active');
   navbar.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .header-2').classList.add('active');
   } else {
      document.querySelector('.header .header-2').classList.remove('active');
   }
}

// Dark mode toggle
document.getElementById('darkModeToggle').addEventListener('click', function () {
   document.body.classList.toggle('dark-mode');
});
function toggleShare() {
   document.getElementById("shareContent").classList.toggle("show");
}

// Optional: close dropdown if clicked outside
window.onclick = function(e) {
   if (!e.target.closest('#shareDropdown')) {
      document.getElementById("shareContent").classList.remove("show");
   }
}
function toggleShare() {
   document.getElementById("shareContent").classList.toggle("show");
}

window.onclick = function(event) {
   if (!event.target.closest('#shareDropdown')) {
      document.getElementById("shareContent").classList.remove('show');
   }
};

// Native share for phones
function shareNative() {
   if (navigator.share) {
      navigator.share({
         title: 'Check this out!',
         text: 'Here’s something interesting!',
         url: 'https://yourlink.com',
      }).catch(err => {
         console.log('Share failed:', err);
      });
   } else {
      alert('Native sharing not supported on this device.');
   }
}

// Copy to clipboard
function copyLink(event) {
   event.preventDefault();
   const dummy = document.createElement("input");
   document.body.appendChild(dummy);
   dummy.value = "https://yourlink.com";
   dummy.select();
   document.execCommand("copy");
   document.body.removeChild(dummy);
   alert("Link copied to clipboard!");
}
