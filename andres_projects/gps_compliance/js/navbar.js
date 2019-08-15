window.onscroll = function() {
  var nav = document.getElementById('navbar');
  // console.log(window.pageYOffset);
  if ( window.pageYOffset > scrollHeight ) {
    nav.classList.add('nav-blue-scrolled');
  } else {
    nav.classList.remove('nav-blue-scrolled');
  }
}
document.addEventListener('DOMContentLoaded', function() {
  // Mobile
  var mobileNavbarElems = document.querySelectorAll('.sidenav');
  var mobileNavbaroptions = {};
  var mobileNavbarInstances = M.Sidenav.init(mobileNavbarElems, mobileNavbaroptions);
  // Dropdown
  var navbarDropdownElems = document.querySelectorAll('.dropdown-trigger');
  var navbarDropdownOptions = {
    constrainWidth: false,
    coverTrigger: false,
    hover: true
  };
  var navbarDropdownInstances = M.Dropdown.init(navbarDropdownElems, navbarDropdownOptions);
});