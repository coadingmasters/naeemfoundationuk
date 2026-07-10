// Naeem Foundation — front-end interactions

document.addEventListener('DOMContentLoaded', () => {
    setupMobileMenu();
    setupSubnav();
    setupTabs();
    setupReveal();
    setupChoiceGroups();
    setupCustomSelects();
    setupScrollTop();
    setupSlideCarousel(document.querySelector('[data-carousel="hero"]'), 5000);
    setupSlideCarousel(document.querySelector('[data-carousel="appeals"]'));
    setupTrackCarousel(document.querySelector('[data-carousel="causes"]'));
});

/* ---------- Custom brand-styled dropdowns (Quick Donate) ---------- */
function setupCustomSelects() {
    const roots = [...document.querySelectorAll('[data-cselect]')];
    if (!roots.length) return;

    const closeAll = (except) => {
        roots.forEach((el) => {
            if (el !== except) {
                el.classList.remove('is-open');
                const b = el.querySelector('[data-cselect-btn]');
                if (b) b.setAttribute('aria-expanded', 'false');
            }
        });
    };

    roots.forEach((root) => {
        const btn = root.querySelector('[data-cselect-btn]');
        const label = root.querySelector('[data-cselect-label]');
        const input = root.querySelector('[data-cselect-input]');
        const opts = [...root.querySelectorAll('.nf-cselect__opt')];
        if (!btn || !label) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = root.classList.contains('is-open');
            closeAll(root);
            root.classList.toggle('is-open', !isOpen);
            btn.setAttribute('aria-expanded', String(!isOpen));
        });

        opts.forEach((opt) => {
            opt.addEventListener('click', () => {
                opts.forEach((o) => o.classList.remove('is-selected'));
                opt.classList.add('is-selected');
                label.textContent = opt.textContent.trim();
                btn.classList.remove('nf-cselect__btn--placeholder');
                if (input) input.value = opt.getAttribute('data-value') || opt.textContent.trim();
                root.classList.remove('is-open');
                btn.setAttribute('aria-expanded', 'false');
            });
        });
    });

    document.addEventListener('click', () => closeAll());
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });
}

/* ---------- Scroll-to-top with circular progress ring ---------- */
function setupScrollTop() {
    const btn = document.getElementById('scrollTop');
    if (!btn) return;

    const progress = btn.querySelector('.nf-scrolltop__progress');
    const CIRCUMFERENCE = 131.95; // 2 * π * r (r = 21)
    let ticking = false;

    const update = () => {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const scrollable = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const ratio = scrollable > 0 ? Math.min(scrollTop / scrollable, 1) : 0;

        if (progress) {
            progress.style.strokeDashoffset = String(CIRCUMFERENCE * (1 - ratio));
        }
        btn.classList.toggle('is-visible', scrollTop > 250);
        ticking = false;
    };

    const onScroll = () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    };

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
}

/* ---------- Donation widget choice groups (frequency + amount) ---------- */
function setupChoiceGroups() {
    document.querySelectorAll('[data-choice-group]').forEach((group) => {
        const btns = [...group.querySelectorAll('[data-choice]')];
        const input = group.querySelector('[data-choice-input]');

        btns.forEach((btn) => {
            btn.addEventListener('click', () => {
                btns.forEach((b) => b.classList.remove('is-selected'));
                btn.classList.add('is-selected');

                // Mirror the selection into the hidden field so the form submits it.
                if (input && btn.dataset.value !== undefined) {
                    input.value = btn.dataset.value;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    });

    setupCustomAmount();
}

/* ---------- "Other" amount field on the donate widget ---------- */
function setupCustomAmount() {
    document.querySelectorAll('[data-donate-form]').forEach((form) => {
        const amount = form.querySelector('[data-amount-input]');
        const wrap = form.querySelector('[data-custom-amount]');
        const custom = form.querySelector('[data-custom-amount-input]');
        if (!amount || !wrap || !custom) return;

        amount.addEventListener('change', () => {
            const isOther = amount.value === 'other';
            wrap.classList.toggle('hidden', !isOther);

            if (isOther) {
                // Hand control of the amount over to the typed value.
                amount.value = custom.value || '';
                custom.focus();
            }
        });

        custom.addEventListener('input', () => {
            amount.value = custom.value;
        });
    });
}

/* ---------- Scroll-in reveal animation ---------- */
function setupReveal() {
    const els = document.querySelectorAll('.nf-reveal');
    if (!els.length) return;

    if (!('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    els.forEach((el) => observer.observe(el));
}

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

/* ---------- Mobile sub-nav accordions ("Who We Are" etc.) ---------- */
function setupSubnav() {
    document.querySelectorAll('[data-subnav-toggle]').forEach((toggle) => {
        const panel = toggle.parentElement.querySelector('[data-subnav]');
        const chev = toggle.querySelector('[data-subnav-chev]');
        if (!panel) return;

        toggle.addEventListener('click', () => {
            const open = panel.classList.toggle('hidden') === false;
            panel.classList.toggle('flex', open);
            toggle.setAttribute('aria-expanded', String(open));
            if (chev) chev.style.transform = open ? 'rotate(180deg)' : '';
        });
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
        slides.forEach((s, n) => s.classList.toggle('is-active', n === index));
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
