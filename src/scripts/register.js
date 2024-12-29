const form = document.getElementById("form");
const emailInput = document.getElementById("emailInput");

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

form.addEventListener("submit", (e) => {
  e.preventDefault();
  if (emailRegex.test(emailInput)) {
    alert("Email is valid!");
    e.target.submit();
  } else {
    alert("Please enter a valid email address.");
  }
});
