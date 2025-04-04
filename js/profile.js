document.addEventListener("DOMContentLoaded", () => {
  // ✅ Password Modal Logic
  const modal = document.getElementById("password-modal");
  const openBtn = document.getElementById("change-password-btn");
  const closeBtn = document.getElementById("close-password-modal");

  if (openBtn && closeBtn && modal) {
    openBtn.addEventListener("click", () => {
      modal.classList.remove("hidden");
    });

    closeBtn.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("hidden");
      }
    });
  }

  // ✅ Bootstrap handles dropdown functionality. No manual JS needed for profile dropdown!
});
