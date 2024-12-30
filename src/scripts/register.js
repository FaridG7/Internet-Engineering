document.getElementById("form").addEventListener("submit", (e) => {
  const passInput = document.getElementById("passInput");
  const repeatPassInput = document.getElementById("repeatPassInput");

  if (passInput.value !== repeatPassInput.value) {
    e.preventDefault();
    alert("Passwords don't match.");
  }
});
