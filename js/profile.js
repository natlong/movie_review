// Toggle profile dropdown when the profile icon (or container) is clicked
const profileContainer = document.getElementById("profile-container");
const profileDropdown = document.getElementById("profile-dropdown");

profileContainer.addEventListener("click", (e) => {
  profileDropdown.classList.toggle("hidden");
  // Prevent event propagation so clicking inside the dropdown doesn't immediately close it
  e.stopPropagation();
});

// Close the dropdown when clicking anywhere else on the document
document.addEventListener("click", () => {
  if (!profileDropdown.classList.contains("hidden")) {
    profileDropdown.classList.add("hidden");
  }
});


document.addEventListener("DOMContentLoaded", () => {
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
});
