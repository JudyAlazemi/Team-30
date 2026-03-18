document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("products-list");

  function getPrice(card) {
    const priceText = card.querySelector(".product-price")?.textContent || "£0";
    return parseFloat(priceText.replace("£", "").trim()) || 0;
  }

  function sortProductsLowToHigh() {
    const products = Array.from(container.querySelectorAll(".product-card"));

    products.sort((a, b) => {
      return getPrice(a) - getPrice(b);
    });

    products.forEach(product => container.appendChild(product));
  }

  sortProductsLowToHigh();
});