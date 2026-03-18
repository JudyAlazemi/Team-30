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

// USER BUTTON LOGIC
(function () {
  const userBtn  = document.getElementById("userBtn");
  const userIcon = document.getElementById("userIcon");
  const userText = document.getElementById("userText");

  function setIcon(imgEl, active) {
    if (!imgEl) return;
    imgEl.src = active ? imgEl.dataset.srcActive : imgEl.dataset.srcInactive;
  }

  function applyUserUI(role) {
    if (!userBtn || !userIcon || !userText) return;

    if (role === "admin") {
      userBtn.href = "admin_dashboard.php";
      userText.textContent = "Admin Panel";
      userBtn.setAttribute("aria-pressed", "true");
      userBtn.dataset.role = "admin";
      setIcon(userIcon, true);
      return;
    }

    if (role === "customer") {
      userBtn.href = "customer_dashboard.php";
      userText.textContent = "My Account";
      userBtn.setAttribute("aria-pressed", "true");
      userBtn.dataset.role = "customer";
      setIcon(userIcon, true);
      return;
    }

    userBtn.href = "login.html";
    userText.textContent = "Sign in";
    userBtn.setAttribute("aria-pressed", "false");
    userBtn.dataset.role = "guest";
    setIcon(userIcon, false);
  }

  const roleFromServer =
    window.userData && window.userData.role
      ? window.userData.role
      : (userBtn?.dataset.role || "guest");

  applyUserUI(roleFromServer);
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

//Dark Mode
(function () {
  const toggleBtn = document.getElementById("theme-switch");
  const STORAGE_KEY = "darkmode";

  function applyTheme(isDark) {
    document.body.classList.toggle("darkmode", isDark);
    localStorage.setItem(STORAGE_KEY, isDark ? "active" : "inactive");

    if (toggleBtn) {
      toggleBtn.setAttribute("aria-pressed", isDark ? "true" : "false");
    }
  }

  // Apply saved theme immediately
  const saved = localStorage.getItem(STORAGE_KEY);
  applyTheme(saved === "active");

  // If no button on this page, stop here (prevents errors)
  if (!toggleBtn) return;

  toggleBtn.addEventListener("click", function () {
    const isDark = document.body.classList.contains("darkmode");
    applyTheme(!isDark);
  });
})();

/// FAVOURITES LINK (heart icon + drawer menu link)
(function () {
  const favBtn  = document.getElementById("favBtn");
  const favIcon = document.getElementById("favIcon");

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

  // Check login state for favourites
  async function updateFavLinks() {
    try {
      const res = await fetch("check_login.php", {
        cache: "no-store",
        credentials: "same-origin"
      });
      const data = await res.json();
      
      applyFavUI(!!data.loggedIn);
      return !!data.loggedIn;
    } catch (err) {
      applyFavUI(false);
      return false;
    }
  }

  if (favBtn) {
    favBtn.addEventListener("click", async (e) => {
      e.preventDefault();
      const loggedIn = await updateFavLinks();
      window.location.href = loggedIn ? "customer_favourites.php" : "favourites.php";
    });
  }

  // Initial load
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", updateFavLinks);
  } else {
    updateFavLinks();
  }
  window.addEventListener("pageshow", updateFavLinks);
})();