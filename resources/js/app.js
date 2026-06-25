// Naeem Foundation — front-end interactions

document.addEventListener('DOMContentLoaded', () => {
    setupMobileMenu();
    setupTabs();
    setupSlideCarousel(document.querySelector('[data-carousel="hero"]'), 5000);
    setupSlideCarousel(document.querySelector('[data-carousel="appeals"]'));
    setupTrackCarousel(document.querySelector('[data-carousel="causes"]'));
});

/* ---------- Tabs (About page: About & History / Vision / Mission) ---------- */
function setupTabs() {
    document.querySelectorAll('[data-tabs]').forEach((root) => {
        const btns = [...root.querySelectorAll('[data-tab-btn]')];
        const panels = [...root.querySelectorAll('[data-tab-panel]')];
        if (!btns.length) return;

        btns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const key = btn.getAttribute('data-tab-btn');
                btns.forEach((b) => b.classList.toggle('is-active', b === btn));
                panels.forEach((p) => p.classList.toggle('hidden', p.getAttribute('data-tab-panel') !== key));
            });
        });
    });
}

/* ---------- Mobile menu ---------- */
function setupMobileMenu() {
    const toggle = document.querySelector('[data-menu-toggle]');
    const panel = document.querySelector('[data-menu-panel]');
    if (!toggle || !panel) return;

    toggle.addEventListener('click', () => {
        const open = panel.classList.toggle('hidden') === false;
        toggle.setAttribute('aria-expanded', String(open));
    });
}

/* ---------- Slide carousel (one slide per view: hero, appeals) ---------- */
function setupSlideCarousel(root, autoMs = 0) {
    if (!root) return;
    const track = root.querySelector('[data-track]');
    const slides = [...root.querySelectorAll('[data-slide]')];
    if (!track || slides.length <= 1) return;

    let index = 0;

    // Optional indicator dots
    const dotsWrap = root.querySelector('[data-dots]');
    const dots = [];
    if (dotsWrap) {
        dotsWrap.innerHTML = '';
        slides.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'nf-dot';
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            dot.addEventListener('click', () => go(i));
            dotsWrap.appendChild(dot);
            dots.push(dot);
        });
    }

    const go = (i) => {
        index = (i + slides.length) % slides.length;
        track.style.transform = `translateX(-${index * 100}%)`;
        dots.forEach((d, n) => d.classList.toggle('is-active', n === index));
    };

    root.querySelector('[data-prev]')?.addEventListener('click', () => go(index - 1));
    root.querySelector('[data-next]')?.addEventListener('click', () => go(index + 1));

    go(0);

    if (autoMs > 0) {
        let timer = setInterval(() => go(index + 1), autoMs);
        root.addEventListener('mouseenter', () => clearInterval(timer));
        root.addEventListener('mouseleave', () => (timer = setInterval(() => go(index + 1), autoMs)));
    }
}

/* ---------- Track carousel (causes) ---------- */
function setupTrackCarousel(root) {
    if (!root) return;
    const track = root.querySelector('[data-track]');
    const cards = [...root.querySelectorAll('[data-card]')];
    if (!track || cards.length === 0) return;

    let index = 0;

    const perView = () => {
        const w = window.innerWidth;
        if (w >= 1024) return 4;
        if (w >= 640) return 2;
        return 1;
    };

    const maxIndex = () => Math.max(0, cards.length - perView());

    const update = () => {
        index = Math.min(index, maxIndex());
        // Each step moves exactly one card width (100 / cards-per-view)
        const offset = index * (100 / perView());
        track.style.transform = `translateX(-${offset}%)`;
    };

    root.querySelector('[data-prev]')?.addEventListener('click', () => {
        index = Math.max(0, index - 1);
        update();
    });
    root.querySelector('[data-next]')?.addEventListener('click', () => {
        index = Math.min(maxIndex(), index + 1);
        update();
    });

    window.addEventListener('resize', update);
    update();
}
