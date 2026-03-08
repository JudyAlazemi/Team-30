document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("newsletterForm");
    const emailInput = document.getElementById("newsletterEmail");
    const msg = document.getElementById("newsletterMsg");

    if (!form || !emailInput || !msg) return;

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);

        if (!valid) {
            msg.textContent = "Please enter a valid email (e.g. name@example.com).";
            msg.style.color = "#8b2d2d";
            return;
        }

        alert("Thank you! Your email has been signed up to the newsletter.");

        msg.textContent = "You have successfully signed up to the newsletter.";
        msg.style.color = "#2c5e3f";

        form.reset();
    });
});