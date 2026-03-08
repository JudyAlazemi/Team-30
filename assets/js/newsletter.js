document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("newsletterForm");
    const emailInput = document.getElementById("newsletterEmail");
    const popup = document.getElementById("newsletterPopup");

    if (!form || !emailInput || !popup) return;

    form.addEventListener("submit", (e) => {
        e.preventDefault(); // stop page reload
        const button = form.querySelector("button");
        button.disabled = true;

        const email = emailInput.value.trim();

        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);

        if (!valid) return;

        popup.classList.add("show");

        setTimeout(() => {
            popup.classList.remove("show");
            button.disabled = false;
        }, 5000);

        form.reset();
    });

});