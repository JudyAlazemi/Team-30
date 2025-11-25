document.addEventListener("DOMContentLoaded", () => {
    
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        form.addEventListener("submit", (e) => {
            let valid = true;

            const inputs = form.querySelectorAll("input");

            inputs.forEach(input => {
                const value = input.value.trim();
                const type = input.getAttribute("type");
                const placeholder = input.placeholder;

                // Reset state
                input.style.border = "1px solid #ccc";

                // Required check
                if (value === "") {
                    markError(input, "This field is required");
                    valid = false;
                    return;
                }

                // Email validation
                if (type === "email") {
                    const emailPattern = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i;
                    if (!emailPattern.test(value)) {
                        markError(input, "Invalid email");
                        valid = false;
                    }
                }

                // Password match (signup + checkout)
                if (input.id === "confirmPassword") {
                    let pw = form.querySelector("#password")?.value;
                    if (pw && pw !== value) {
                        markError(input, "Passwords do not match");
                        valid = false;
                    }
                }

                // Postcode (UK)
                if (placeholder === "B1 1AA") {
                    const pcPattern = /^[A-Z]{1,2}[0-9]{1,2}[A-Z]?\s?[0-9][A-Z]{2}$/i;
                    if (!pcPattern.test(value)) {
                        markError(input, "Invalid postcode");
                        valid = false;
                    }
                }

                // Card number – remove spaces
                if (placeholder === "1234 5678 9012 3456") {
                    const clean = value.replace(/\s/g, "");
                    if (!/^\d{16}$/.test(clean)) {
                        markError(input, "Card must be 16 digits");
                        valid = false;
                    }
                }

                // Expiry date MM/YY
                if (placeholder === "MM/YY") {
                    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(value)) {
                        markError(input, "Format MM/YY");
                        valid = false;
                    }
                }

                // CVV
                if (placeholder === "123") {
                    if (!/^\d{3,4}$/.test(value)) {
                        markError(input, "Invalid CVV");
                        valid = false;
                    }
                }

            });

            if (!valid) {
                e.preventDefault(); // stop form submit
            }
        });
    });

    function markError(input, msg) {
        input.style.border = "2px solid #d4183d";
        input.placeholder = msg;
    }
});
