const menuToggle = document.querySelector(".menu-toggle-button");
const sideMenu = document.querySelector(".sidemenu");

menuToggle.addEventListener("click", function () {
  sideMenu.classList.toggle("show-menu");
});
