const menuToggle = document.querySelector(".menu-toggle-button");
const sideMenu = document.querySelector(".side-menu");

menuToggle.addEventListener("click", function () {
    sideMenu.classList.toggle("show-menu");
});
