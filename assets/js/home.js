(() => {
  const track = document.getElementById('collectionTrack');
  if (!track) return;

  const cards = Array.from(track.querySelectorAll('.product-card'));
  const prev  = document.querySelector('.arrow-btn.prev');
  const next  = document.querySelector('.arrow-btn.next');

  // mark the card closest to the track center as active
  function setActive(){
    const tRect = track.getBoundingClientRect();
    const centerX = tRect.left + tRect.width / 2;
    let best = null, bestDist = Infinity, bestIdx = -1;

    cards.forEach((card, i) => {
      const r = card.getBoundingClientRect();
      const cx = r.left + r.width / 2;
      const d = Math.abs(cx - centerX);
      if (d < bestDist) { bestDist = d; best = card; bestIdx = i; }
    });

    cards.forEach(c => c.classList.remove('is-active'));
    if (best) best.classList.add('is-active');
    return bestIdx;
  }

  // scroll until the nearest card is centered after user stops scrolling
  let raf, snapTimer;
  function onScroll(){
    cancelAnimationFrame(raf);
    raf = requestAnimationFrame(setActive);

    clearTimeout(snapTimer);
    snapTimer = setTimeout(() => {
      const active = track.querySelector('.product-card.is-active');
      active?.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    }, 80);
  }

  // arrows: go to previous/next card index
  function goTo(delta){
    const idx = setActive(); // current
    const nextIdx = Math.min(cards.length - 1, Math.max(0, (idx ?? 0) + delta));
    cards[nextIdx].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
  }

  prev?.addEventListener('click', () => goTo(-1));
  next?.addEventListener('click', () => goTo(+1));

  // tap a card to center it
  cards.forEach(card => {
    card.addEventListener('click', () => {
      card.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    });
  });

  track.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', setActive);

  // initial state
  setActive();
})();

// --- SIGNATURE COLLECTION CAROUSEL ---
(() => {
  const track = document.getElementById('signatureTrack');
  if (!track) return;

  const prev = document.querySelector('.signature-arrows .prev');
  const next = document.querySelector('.signature-arrows .next');

  function scrollAmount() {
    const card = track.querySelector('.product-card');
    if (!card) return 0;

    const styles = getComputedStyle(track);
    const gap = parseFloat(styles.gap) || 0;
    return card.getBoundingClientRect().width + gap;
  }

  prev?.addEventListener("click", () => {
    track.scrollBy({ left: -scrollAmount(), behavior: "smooth" });
  });

  next?.addEventListener("click", () => {
    track.scrollBy({ left: scrollAmount(), behavior: "smooth" });
  });
})();

// ---- QUIZ POPUP (single source of truth) ----
document.addEventListener('DOMContentLoaded', () => {
  const openBtn  = document.querySelector('.cta-quiz__btn');
  const modal    = document.getElementById('quizModal');
  if (!openBtn || !modal) return;

  const steps    = [...modal.querySelectorAll('.quiz-step')];
  const backdrop = modal.querySelector('.quiz-backdrop');
  const closeEls = modal.querySelectorAll('[data-close-quiz]');
  let current = 1;

  const show = (n) => {
    steps.forEach(s => s.classList.remove('active'));
    modal.querySelector(`.quiz-step[data-step="${n}"]`)?.classList.add('active');
    current = n;
  };

  const open = (e) => {
    e?.preventDefault();

    // ★ RESET RADIO BUTTONS
    modal.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
    
    // ★ RESET TO STEP 1
    show(1);

    // open modal
    modal.classList.add('active');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  };

  const close = ()  => { modal.classList.remove('active'); document.body.style.overflow=''; };

  openBtn.addEventListener('click', open);
  backdrop.addEventListener('click', close);
  closeEls.forEach(el => el.addEventListener('click', close));
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('active')) close(); });

  modal.addEventListener('click', e => {

    if (e.target.classList.contains('quiz-next')) {
      // require an answer on the current step
      const active = modal.querySelector(`.quiz-step[data-step="${current}"]`);
      const chosen = [...active.querySelectorAll('input[type="radio"]')].some(r => r.checked);
      if (!chosen) return;
      show(current + 1);
    }

    if (e.target.classList.contains('quiz-back'))  
      show(Math.max(1, current - 1));

    if (e.target.classList.contains('quiz-finish')) {

      const q1 = (modal.querySelector('input[name="q1"]:checked')||{}).value;
      const q2 = (modal.querySelector('input[name="q2"]:checked')||{}).value;
      const q3 = (modal.querySelector('input[name="q3"]:checked')||{}).value;
      const q4 = (modal.querySelector('input[name="q4"]:checked')||{}).value;

      let suggestion = 'Sabil No. 1';
      if (q1==='fresh')  suggestion='Ocean Mist';
      if (q1==='floral') suggestion='Jasmin Dreams';
      if (q1==='woody')  suggestion='Velvet Musk';
      if (q1==='spicy')  suggestion='Spice Route';

      modal.querySelector('#quizResultText').textContent =
        `We think you’ll love ${suggestion}.`;

      //  BACKEND FETCH  (quiz)
      fetch("quiz_backend.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          answer1: q1,
          answer2: q2,
          answer3: q3,
          answer4: q4,
          suggestion: suggestion
        })
      });

      steps.forEach(s => s.classList.remove('active'));
      modal.querySelector('.quiz-step[data-step="result"]').classList.add('active');
    }
  });
});
