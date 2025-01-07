<header style="height: 10vh;background-color: var(--primary-color);display: flex;flex-direction: row;">
  <a href="/dashboard">
    <img src="../assets/images/logo.png" alt="" style="height: 100%; width:auto;"/>
  </a>
  <nav style="display: flex;flex-direction: row;width: 80%;">
    <a href="/src/logout"><button>خروج &rarr;</button></a>
    <button id="theme-toggle" aria-label="Toggle Dark Mode">
      <span id="theme-icon" class="icon-sun">☀️</span>
    </button>
    <a href="/src/movies/" style="margin-right: 20px;font-size: large;text-decoration: none;margin-top:auto;margin-bottom:auto;">فیلم‌ها</a>
    <a href="/src/support/" style="margin-right: auto;text-decoration: none;margin-top:auto;margin-bottom:auto;"><span>?</span></a>
    <a href="/src/settings/" style="margin-right: 1%;text-decoration: none;margin-top:auto;margin-bottom:auto;"><span>&#9881;</span></a>
  </nav>
</header>
<script>
const themeToggle = document.getElementById("theme-toggle");
const rootElement = document.documentElement;
const themeIcon = document.getElementById("theme-icon");

themeToggle.addEventListener("click", () => {
  if (rootElement.getAttribute("data-theme") === "dark") {
    rootElement.removeAttribute("data-theme");
    localStorage.setItem("theme", "light");
    themeIcon.textContent = "☀️";
  } else {
    rootElement.setAttribute("data-theme", "dark");
    localStorage.setItem("theme", "dark");
    themeIcon.textContent = "🌙";
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    rootElement.setAttribute("data-theme", savedTheme);
    themeIcon.textContent = savedTheme === "dark" ? "🌙" : "☀️";
  }
});
</script>