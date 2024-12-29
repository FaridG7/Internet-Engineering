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

document.querySelectorAll(".accordion-header").forEach((header) => {
  header.addEventListener("click", () => {
    const content = header.nextElementSibling;

    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
});
