const menuMore = document.querySelector(".menu-more");
const nav = document.querySelector(".nav-full.bg-wrap");
menuMore.addEventListener("click", function () {
  nav.classList.toggle("show");
  menuMore.classList.toggle("show");
});
document.addEventListener("DOMContentLoaded", function () {
  const menuBtn = document.getElementById("menuShow");
  const menuWrap = document.getElementById("menuWrapMobile");
  const closeBtn = document.getElementById("menuCloseMobile");

  menuBtn.addEventListener("click", () => {
    menuWrap.classList.add("active");
  });

  closeBtn.addEventListener("click", () => {
    menuWrap.classList.remove("active");
  });
});
const login = document.querySelector(".auth-placeholder");
const loginMobile = document.querySelector(".auth-placeholder-mobile");
login.addEventListener("click", function(){
  document.getElementById("loginOverlay").classList.add("active");
});
loginMobile.addEventListener("click", function(){
  document.getElementById("loginOverlay").classList.add("active");
});
document.getElementById("closeLogin").addEventListener("click", function () {
  document.getElementById("loginOverlay").classList.remove("active");
});
document.getElementById('tab-login').onclick = function () {
  this.classList.add('active', 'dt-text-[#0F6C32]', 'dt-border-b-[4px]', 'dt-border-[#158E42]');
  this.classList.remove('dt-text-[#A0A4A8]');

  const tabRegister = document.getElementById('tab-register');
  tabRegister.classList.remove('active', 'dt-text-[#0F6C32]', 'dt-border-b-[4px]', 'dt-border-[#158E42]');
  tabRegister.classList.add('dt-text-[#A0A4A8]');

  document.getElementById('form-login').classList.remove('hidden');
  document.getElementById('form-register').classList.add('hidden');
};

document.getElementById('tab-register').onclick = function () {
  this.classList.add('active', 'dt-text-[#0F6C32]', 'dt-border-b-[4px]', 'dt-border-[#158E42]');
  this.classList.remove('dt-text-[#A0A4A8]');

  const tabLogin = document.getElementById('tab-login');
  tabLogin.classList.remove('active', 'dt-text-[#0F6C32]', 'dt-border-b-[4px]', 'dt-border-[#158E42]');
  tabLogin.classList.add('dt-text-[#A0A4A8]');

  document.getElementById('form-register').classList.remove('hidden');
  document.getElementById('form-login').classList.add('hidden');
};
