document.getElementById("request-form").addEventListener("submit", function(e) {
    e.preventDefault();
    const requestInput = document.getElementById("request-input");
    const movieName = requestInput.value.trim();
    if (movieName !== "") {
      alert(`Request for "${movieName}" received!`);
      requestInput.value = "";
    }
  });
  