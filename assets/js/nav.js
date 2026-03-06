// MENU DRAWER (hamburger is the only toggle)
const btn = document.querySelector('.menu-toggle');
const menuDrawer = document.getElementById('menuDrawer');

function openDrawer() {
  menuDrawer?.classList.add('is-open');
  document.body.classList.add('drawer-open');
  btn?.classList.add('is-open');
  btn?.setAttribute('aria-expanded', 'true');
  btn?.setAttribute('aria-label', 'Close menu');
  menuDrawer?.setAttribute('aria-hidden', 'false');
}

function closeDrawer() {
  menuDrawer?.classList.remove('is-open');
  document.body.classList.remove('drawer-open');
  btn?.classList.remove('is-open');
  btn?.setAttribute('aria-expanded', 'false');
  btn?.setAttribute('aria-label', 'Open menu');
  menuDrawer?.setAttribute('aria-hidden', 'true');
}

btn?.addEventListener('click', () => {
  const open = menuDrawer?.classList.contains('is-open');
  open ? closeDrawer() : openDrawer();
});

// click backdrop or ESC to close
menuDrawer?.addEventListener('click', (e) => {
  if (e.target.matches('[data-close-drawer]')) closeDrawer();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && menuDrawer?.classList.contains('is-open')) closeDrawer();
});

// USER BUTTON LOGIC (session-based)
(function () {
  const $ = (sel) => document.querySelector(sel);

  const userBtn  = $("#userBtn");
  const userIcon = $("#userIcon");
  const userText = $("#userText");

  function setIcon(imgEl, active) {
    if (!imgEl) return;
    imgEl.src = active ? imgEl.dataset.srcActive : imgEl.dataset.srcInactive;
  }

  async function updateUserUI() {
    if (!userBtn || !userIcon || !userText) return;

    try {
      const res = await fetch("check_login.php", {
        cache: "no-store",
        credentials: "same-origin"
      });
      const data = await res.json();

      if (data.loggedIn) {
        userBtn.href = "customer_dashboard.php";
        userText.textContent = "My Account";
        userBtn.setAttribute("aria-pressed", "true");
        setIcon(userIcon, true);
      } else {
        userBtn.href = "login.html";
        userText.textContent = "Sign in";
        userBtn.setAttribute("aria-pressed", "false");
        setIcon(userIcon, false);
      }
    } catch (err) {
      userBtn.href = "login.html";
      userText.textContent = "Sign in";
      userBtn.setAttribute("aria-pressed", "false");
      setIcon(userIcon, false);
    }
  }

  updateUserUI();
})();

// --- SEARCH SLIDE-IN ---
const searchBtn      = document.getElementById("searchBtn");
const navSearchInput = document.getElementById("navSearchInput");

function closeSearch(){
  navSearchInput?.classList.remove("open");
  searchBtn?.setAttribute("aria-expanded", "false");
}
function openSearch(){
  navSearchInput?.classList.add("open");
  searchBtn?.setAttribute("aria-expanded", "true");
  navSearchInput?.focus();
}

searchBtn?.addEventListener("click", (e) => {
  e.preventDefault();
  e.stopPropagation();

  const open = navSearchInput?.classList.toggle("open");
  searchBtn?.setAttribute("aria-expanded", open ? "true" : "false");
  if (open) navSearchInput?.focus();
});

navSearchInput?.addEventListener("click", (e) => e.stopPropagation());

navSearchInput?.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeSearch();
  if (e.key === "Enter") {
    const q = navSearchInput.value.trim();
    if (q) window.location.href = `products.php?search=${encodeURIComponent(q)}`;
  }
});

document.addEventListener("click", () => {
  if (navSearchInput?.classList.contains("open")) closeSearch();
});

/// FAVOURITES LINK (heart icon + drawer menu link) ✅ always correct, even if clicked fast
(function () {
  const favBtn  = document.getElementById("favBtn");
  const favIcon = document.getElementById("favIcon");

  // track state so click can redirect correctly
  let loginState = null; // null = unknown, true/false = known

  function getDrawerFavLinks() {
    return document.querySelectorAll(
      '#menuDrawer .drawer__nav a[href="favourites.php"], #menuDrawer .drawer__nav a[href="customer_favourites.php"]'
    );
  }

  function applyFavUI(loggedIn) {
    const targetHref = loggedIn ? "customer_favourites.php" : "favourites.php";

    if (favBtn) {
      favBtn.href = targetHref;
      favBtn.setAttribute("aria-pressed", loggedIn ? "true" : "false");
    }

    if (favIcon) {
      const src = loggedIn ? favIcon.dataset.srcActive : favIcon.dataset.srcInactive;
      if (src) favIcon.src = src;
    }

    getDrawerFavLinks().forEach(a => a.href = targetHref);
  }

  async function updateFavLinks() {
    try {
      const res = await fetch("check_login.php", {
        cache: "no-store",
        credentials: "same-origin"
      });
      const data = await res.json();

      loginState = !!data.loggedIn;
      applyFavUI(loginState);
      return loginState;
    } catch (err) {
      loginState = false; // safest fallback
      applyFavUI(false);
      return false;
    }
  }

  // ✅ IMPORTANT: intercept click so it never goes to the wrong page
  if (favBtn) {
    favBtn.addEventListener("click", async (e) => {
      // if state not ready yet, prevent wrong navigation and redirect correctly
      if (loginState === null) {
        e.preventDefault();
        const loggedIn = await updateFavLinks();
        window.location.href = loggedIn ? "customer_favourites.php" : "favourites.php";
        return;
      }

      // if state known, force correct href just before navigating
      favBtn.href = loginState ? "customer_favourites.php" : "favourites.php";
    });
  }

  // run on load + when coming back (bfcache)
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", updateFavLinks);
  } else {
    updateFavLinks();
  }
  window.addEventListener("pageshow", updateFavLinks);

  // optional: allow manual refresh
  window.updateFavButton = updateFavLinks;
})();