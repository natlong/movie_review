document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("request-form");
  if (form) {
    form.addEventListener("submit", function() {
      const input = document.getElementById("request-input");
      alert(`Request for "${input.value}" received!`);
    });
  }
});
