const city = "Hà Nội";
const apiKey = "4c85b45cb3ead594d2ff3465afbf0f0c"; 
const weatherBox = document.querySelector(".weather-box");

function formatDateVN(date) {
  return date.toLocaleDateString("vi-VN", {
    weekday: "long",
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
}

function displayWeather(data) {
  const today = new Date();
  weatherBox.innerHTML = `
      <div>
        <span class="weather-text weather-location">${data.city}</span>
        <span class="weather-text weather-date">${formatDateVN(today)}</span>
      </div>
      <div class="weather-divider"></div>
      <div class="weather-icon">
        <img src="https://openweathermap.org/img/wn/${data.icon}@2x.png" alt="${data.description}" width="40" height="40">
      </div>
      <span class="weather-text weather-temperature">${data.temp}°C</span>
    `;
}

function getWeather(city) {
  const url = `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(
    city
  )}&units=metric&appid=${apiKey}&lang=vi`;
  return fetch(url)
    .then((res) => res.json())
    .then((data) => ({
      city: city,
      temp: Math.round(data.main.temp),
      icon: data.weather[0].icon,
      description: data.weather[0].description,
    }))
    .catch((err) => {
      console.error("Lỗi khi lấy thời tiết:", err);
    });
}

async function initWeather() {
  const weather = await getWeather(city);
  if (weather) {
    displayWeather(weather);
  }
}

initWeather();

function processSearch(value) { value = (value || '').trim(); if (value && value.length > 0) { value = encodeURIComponent(value); value = value.trim().replaceAll("%20", "+"); window.location.href = (value.length > 0) ? `/tim-kiem/${value}.htm` : `/tim-kiem.htm`; } }
document.querySelector('.input-search-header')?.addEventListener('keypress', (event) => { let value = (event.target.value || '').trim(); if (event.keyCode === 13 && value && value.length > 0) { processSearch(value); } })
document.querySelector('.input-search-header')?.addEventListener('focus', (event) => { document.querySelector('.box-trend-header')?.classList.add('active'); })
document.addEventListener('click', (event) => {
    let elem = event.target; if (elem) {
        let boxTrendElem = document.querySelector('.box-trend-header'); let boxSearchHeader = document.querySelector('.box-search-header'); if (elem.classList.contains('btn-search-header') || elem.closest('.btn-search-header')) {
            if (boxSearchHeader) {
                let currentIsActive = boxSearchHeader.classList.contains('active'); if (currentIsActive)
                    boxTrendElem?.classList.remove('active'); if (currentIsActive) {
                        let value = document.querySelector('.input-search-header')?.value || ''; if (value && value.length > 0)
                            processSearch(value); else
                            boxSearchHeader.classList.remove('active');
                    }
                else
                    boxSearchHeader.classList.add('active'); setTimeout(() => {
                        if (boxSearchHeader.classList.contains('active'))
                            document.querySelector('.input-search-header')?.focus();
                    }, 300)
            }
            return;
        }
        if (elem?.tagName === 'A')
            return; if (elem.classList.contains('input-search-header') || elem.closest('.input-search-header') || elem.classList.contains('box-trend-header') || elem?.closest('.box-trend-header')) { event.preventDefault(); return; }
        if (boxSearchHeader) { boxSearchHeader.classList.remove('active'); boxTrendElem?.classList?.remove('active'); }
    }
})

const menuMore = document.querySelector('.menu-more');
const nav = document.querySelector('.nav-full.bg-wrap');
menuMore.addEventListener('click', function () {
  nav.classList.toggle('show');
  menuMore.classList.toggle('show');
});



