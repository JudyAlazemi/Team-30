/* ============================================================
   1) LOAD FAVOURITES FROM LOCALSTORAGE
   ============================================================ */

// Get saved favourites from localStorage (browser storage).
// If nothing is saved yet, use an empty list.
let favourites = JSON.parse(localStorage.getItem("favourites")) || [];


/* ============================================================
   2) ADD TO FAVOURITES  (your teammate will call this)
   ============================================================ */

// Example your teammate will call from a product page:
// addToFavourites({
//   id: "p1",
//   name: "Perfume name",
//   brand: "Sabil",
//   notes: "Soft floral, musk",
//   price: "£50"
// });

function addToFavourites(product) {
  favourites.push(product);

  // Save updated favourites list
  localStorage.setItem("favourites", JSON.stringify(favourites));

  // Refresh page if user is on the favourites page
  renderFavourites();
}


/* ============================================================
   3) REMOVE FROM FAVOURITES
   ============================================================ */

function removeFromFavourites(id) {

  // Remove the perfume with the matching id
  favourites = favourites.filter(function (fav) {
    return fav.id !== id;
  });

  // Save updated list
  localStorage.setItem("favourites", JSON.stringify(favourites));

  // Update display
  renderFavourites();
}


/* ============================================================
   4) RENDER (DRAW) THE FAVOURITES PAGE
   ============================================================ */

function renderFavourites() {
  var grid = document.getElementById("favourites-grid");
  var empty = document.getElementById("empty-state");

  // If not on the favourites page, do nothing
  if (!grid || !empty) {
    return;
  }

  // When list is empty
  if (!favourites || favourites.length === 0) {
    empty.style.display = "block";   // show empty message
    grid.innerHTML = "";             // clear card area
    return;
  }

  // When list has items
  empty.style.display = "none";
  grid.innerHTML = "";               // clear old cards

  // Create a card for each favourite perfume
  favourites.forEach(function (item) {
    var card = document.createElement("article");
    card.className = "fav-card";

    card.innerHTML =
      '<div class="fav-image"></div>' +
      '<div class="fav-name">' + item.name + '</div>' +
      '<div class="fav-brand">' + item.brand + '</div>' +
      '<div class="fav-notes">' + item.notes + '</div>' +
      '<div class="fav-price">' + item.price + '</div>' +
      '<button type="button" class="fav-remove-btn" aria-label="Remove">♥</button>';

    // When user clicks ♥ remove from favourites
    var removeBtn = card.querySelector(".fav-remove-btn");
    removeBtn.addEventListener("click", function () {
      removeFromFavourites(item.id);
    });

    // Add card to grid
    grid.appendChild(card);
  });
}


/* ============================================================
   5) RUN ON PAGE LOAD
   ============================================================ */

// When the page finishes loading, draw favourites
document.addEventListener("DOMContentLoaded", function () {
  renderFavourites();
});
