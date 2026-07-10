// Naeem Foundation — front-end interactions

document.addEventListener('DOMContentLoaded', () => {
    setupMobileMenu();
    setupSubnav();
    setupTabs();
    setupReveal();
    setupChoiceGroups();
    setupCustomSelects();
    setupPaymentForm();
    setupRamadanScheduler();
    setupCart();
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

/* ---------- Payment form: card formatting, digit guards, submit state ---------- */
function setupPaymentForm() {
    const form = document.querySelector('[data-payment-form]');
    if (!form) return;

    // Group the card number into blocks of four as the donor types.
    const cardNumber = form.querySelector('[data-card-number]');
    if (cardNumber) {
        cardNumber.addEventListener('input', () => {
            const digits = cardNumber.value.replace(/\D/g, '').slice(0, 19);
            cardNumber.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ');
        });
    }

    // CVC is digits only.
    const cvc = form.querySelector('[data-cvc]');
    if (cvc) {
        cvc.addEventListener('input', () => {
            cvc.value = cvc.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    // Prevent double submits and show progress.
    form.addEventListener('submit', () => {
        const btn = form.querySelector('[data-payment-submit]');
        const spinner = form.querySelector('[data-payment-spinner]');
        const label = form.querySelector('[data-payment-label]');
        if (!btn) return;

        btn.disabled = true;
        spinner?.classList.remove('hidden');
        if (label) label.textContent = 'Processing…';
    });
}

/* ---------- Ramadan giving scheduler ---------- */
function setupRamadanScheduler() {
    const root = document.querySelector('[data-ramadan]');
    if (!root) return;

    const nights = Number(root.dataset.nights) || 30;

    const amountBtns = [...root.querySelectorAll('[data-rg-amount]')];
    const boostBtns = [...root.querySelectorAll('[data-rg-boost]')];
    const causeBtns = [...root.querySelectorAll('[data-rg-cause]')];
    const custom = root.querySelector('[data-rg-custom]');
    const totalEl = root.querySelector('[data-rg-total]');
    const boostEl = root.querySelector('[data-rg-boost-amount]');
    const amountInput = root.querySelector('[data-rg-amount-input]');
    const causeInput = root.querySelector('[data-rg-cause-input]');
    const submit = root.querySelector('[data-rg-submit]');

    let daily = Number(custom?.value) || 0;
    let boost = 0;
    let cause = causeBtns.find((b) => b.classList.contains('is-selected'))?.dataset.rgCause ?? '';

    const money = (n) => `£${n.toFixed(2)}`;

    const render = () => {
        const extra = daily * (boost / 100);
        const total = daily * nights + extra;

        if (boostEl) boostEl.textContent = money(extra);
        if (totalEl) totalEl.textContent = money(total);
        if (amountInput) amountInput.value = total.toFixed(2);
        if (causeInput) causeInput.value = `${cause} (Ramadan ${nights} Nights)`;
        if (submit) submit.disabled = !(total > 0);

        // Highlight the preset matching the current daily amount (if any).
        amountBtns.forEach((b) => b.classList.toggle('is-selected', Number(b.dataset.rgAmount) === daily));
    };

    amountBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            daily = Number(btn.dataset.rgAmount);
            if (custom) custom.value = daily;
            render();
        });
    });

    custom?.addEventListener('input', () => {
        daily = Math.max(0, Number(custom.value) || 0);
        render();
    });

    boostBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            boost = Number(btn.dataset.rgBoost);
            boostBtns.forEach((b) => b.classList.toggle('is-selected', b === btn));
            render();
        });
    });

    causeBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            cause = btn.dataset.rgCause;
            causeBtns.forEach((b) => b.classList.toggle('is-selected', b === btn));
            render();
        });
    });

    render();
}

/* ---------- Toast ---------- */
function showToast(message, isError = false) {
    const toast = document.createElement('div');
    toast.className = `nf-toast${isError ? ' nf-toast--error' : ''}`;
    toast.setAttribute('role', 'status');
    toast.textContent = message;
    document.body.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('is-visible'));

    setTimeout(() => {
        toast.classList.remove('is-visible');
        setTimeout(() => toast.remove(), 300);
    }, 3200);
}

/* ---------- Header mini-cart ---------- */
function setupCart() {
    const root = document.querySelector('[data-cart]');
    if (!root) return;

    const toggle = root.querySelector('[data-cart-toggle]');
    const body = root.querySelector('[data-cart-body]');
    const badge = root.querySelector('[data-cart-count]');

    const open = () => {
        root.classList.add('is-open');
        toggle?.setAttribute('aria-expanded', 'true');
    };
    const close = () => {
        root.classList.remove('is-open');
        toggle?.setAttribute('aria-expanded', 'false');
    };

    toggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        root.classList.contains('is-open') ? close() : open();
    });
    root.querySelector('[data-cart-close]')?.addEventListener('click', close);
    document.addEventListener('click', (e) => {
        if (!root.contains(e.target)) close();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });

    const setBadge = (count) => {
        if (!badge) return;
        badge.textContent = count;
        badge.classList.toggle('hidden', !count);
        badge.classList.remove('is-bumped');
        void badge.offsetWidth; // force reflow so the animation restarts
        badge.classList.add('is-bumped');
    };

    const refresh = (data) => {
        if (body && typeof data.html === 'string') body.innerHTML = data.html;
        setBadge(data.count);
        bindRemoveForms();
    };

    const post = async (form) => {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
            credentials: 'same-origin',
        });
        return { ok: res.ok, status: res.status, data: await res.json().catch(() => ({})) };
    };

    // Remove buttons live inside the panel, so rebind after every refresh.
    function bindRemoveForms() {
        root.querySelectorAll('form[data-cart-remove]').forEach((form) => {
            if (form.dataset.bound) return;
            form.dataset.bound = '1';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                try {
                    const { ok, data } = await post(form);
                    if (!ok) throw new Error('failed');
                    refresh(data);
                } catch {
                    form.submit();
                }
            });
        });
    }
    bindRemoveForms();

    // Any "add to basket" form on any page.
    document.querySelectorAll('form[action$="/donate/add"]').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = form.querySelector('[type="submit"]');
            btn && (btn.disabled = true);

            try {
                const { ok, status, data } = await post(form);

                if (status === 422) {
                    const first = Object.values(data.errors ?? {})[0]?.[0] ?? 'Please check your donation amount.';
                    showToast(first, true);
                    return;
                }
                if (!ok) throw new Error('failed');

                refresh(data);
                open();
                showToast(data.message ?? 'Added to your basket.');
            } catch {
                form.submit(); // no JS / network trouble: fall back to a normal post
            } finally {
                btn && (btn.disabled = false);
            }
        });
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
