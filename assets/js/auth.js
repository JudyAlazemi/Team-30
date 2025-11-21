// Side menu open
document.getElementById("menu-btn").addEventListener("click", () => {
    document.getElementById("side-menu").classList.add("active");
    document.getElementById("overlay").classList.add("active");
});

// Side menu close
document.getElementById("close-menu").addEventListener("click", () => {
    document.getElementById("side-menu").classList.remove("active");
    document.getElementById("overlay").classList.remove("active");
});

// Close on overlay
document.getElementById("overlay").addEventListener("click", () => {
    document.getElementById("side-menu").classList.remove("active");
    document.getElementById("overlay").classList.remove("active");
});

// Password toggle
document.querySelectorAll(".toggle-password").forEach(btn => {
    btn.addEventListener("click", () => {
        let target = document.getElementById(btn.dataset.target);

        if (target.type === "password") {
            target.type = "text";
        } else {
            target.type = "password";
        }
    });
});
