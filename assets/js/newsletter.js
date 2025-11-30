document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("newsletterForm");
    const emailInput = document.getElementById("newsletterEmail");
    const msg = document.getElementById("newsletterMsg");

    if (!form || !emailInput || !msg) return; // page has no newsletter form

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);

        if (!valid) {
            msg.textContent = "Please enter a valid email (e.g. name@example.com).";
            msg.style.color = "#8b2d2d";
            return;
        }

        fetch("newsletter_backend.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email })
        })
        .then(res => res.json())
        .then(data => {
            msg.textContent = data.message || "Subscription processed.";
            msg.style.color = (data.status === "success") ? "#2c5e3f" : "#8b2d2d";

            if (data.status === "success") form.reset();
        })
        .catch(() => {
            msg.textContent = "Something went wrong. Please try again.";
            msg.style.color = "#8b2d2d";
        });
    });
});
