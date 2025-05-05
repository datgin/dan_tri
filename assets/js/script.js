const menuMore = document.querySelector('.menu-more');
const nav = document.querySelector('.nav-full.bg-wrap');
menuMore.addEventListener('click', function () {
  nav.classList.toggle('show');
  menuMore.classList.toggle('show');
});
document.getElementById('menuShow').addEventListener('click', function () {
  const menu = document.getElementById('menuWrap');
  menu.style.display = 'block';
});



