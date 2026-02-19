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


// USER BUTTON LOGIC (simple)
(function () {
  const $ = (sel) => document.querySelector(sel);

  const userBtn  = $("#userBtn");
  const userIcon = $("#userIcon");
  const userText = $("#userText");

  function setIcon(imgEl, active) {
    if (!imgEl) return;
    imgEl.src = active ? imgEl.dataset.srcActive : imgEl.dataset.srcInactive;
  }

  // Replace your current "let userName = localStorage.getItem('userName') || '';" with this:
function getSavedName(){
  const raw = localStorage.getItem("userName") || "";
  // try JSON.parse first (handles values saved via JSON.stringify)
  try {
    const parsed = JSON.parse(raw);
    if (typeof parsed === "string") return parsed.trim();
  } catch {}
  // fallback: strip surrounding quotes if present
  return raw.replace(/^"(.*)"$/, "$1").trim();
}

let userName = getSavedName();
// optional: write back the cleaned value so it stays fixed
if (userName) localStorage.setItem("userName", userName);

  function updateUserUI() {
    if (!userBtn || !userIcon || !userText) return;
    const signedIn = !!userName;
    userBtn.setAttribute("aria-pressed", signedIn ? "true" : "false");
    setIcon(userIcon, signedIn);
    userText.textContent = signedIn ? userName : "Sign in";
  }

  updateUserUI();

  userBtn?.addEventListener("click", () => {
    // no popup logic anymore
  });

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
    if (q) window.location.href = `products.html?search=${encodeURIComponent(q)}`;
  }
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






