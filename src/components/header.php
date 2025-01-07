<header style="height: 10vh;background-color: var(--primary-color);display: flex;flex-direction: row;">
  <a href="/logout" style="margin-left: 20px;font-size: small;text-decoration: none;margin-top:auto;margin-bottom:auto;">&rarr;خروج </a>
  <a href="/dashboard">
    <img src="../assets/images/logo.png" alt="" style="height: 100%; width:auto;"/>
  </a>
  <nav style="display: flex;flex-direction: row;width: 80%;">
    <button id="theme-toggle" style="background: none;border: none;cursor: pointer;font-size: 1.5rem;padding: 0.5rem;color: var(--text-color);transition: color 0.3s;">
      <span id="theme-icon">☀️</span>
    </button>
    <a href="/movies/" style="margin-right: 20px;font-size: large;text-decoration: none;margin-top:auto;margin-bottom:auto;">فیلم‌ها</a>
    <a href="/support/" style="margin-right: auto;text-decoration: none;margin-top:auto;margin-bottom:auto;"><span>?</span></a>
    <a href="/settings/" style="margin-right: 1%;text-decoration: none;margin-top:auto;margin-bottom:auto;"><span>&#9881;</span></a>
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