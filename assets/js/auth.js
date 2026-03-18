document.getElementById("menu-btn")?.addEventListener("click", () => {
  document.getElementById("side-menu")?.classList.add("active");
  document.getElementById("overlay")?.classList.add("active");
});

document.getElementById("close-menu")?.addEventListener("click", () => {
  document.getElementById("side-menu")?.classList.remove("active");
  document.getElementById("overlay")?.classList.remove("active");
});

document.getElementById("overlay")?.addEventListener("click", () => {
  document.getElementById("side-menu")?.classList.remove("active");
  document.getElementById("overlay")?.classList.remove("active");
});

// Password toggle
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".toggle-password").forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetId = btn.getAttribute("data-target");
      const input = document.getElementById(targetId);

      if (!input) return;

      input.type = input.type === "password" ? "text" : "password";

      // optional: accessibility state
      btn.setAttribute("aria-pressed", input.type === "text" ? "true" : "false");
    });
  });
});

