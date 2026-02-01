import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Lightweight analytics event tracking
window.dataLayer = window.dataLayer || [];
function trackEvent(event, payload = {}) {
    window.dataLayer.push({ event, ...payload });
    document.dispatchEvent(new CustomEvent('analytics:event', { detail: { event, ...payload } }));
}
window.trackEvent = trackEvent;

document.addEventListener('DOMContentLoaded', () => {
    trackEvent('page_view', { path: window.location.pathname });

    document.querySelectorAll('[data-analytics-event]').forEach((el) => {
        el.addEventListener('click', () => {
            const event = el.getAttribute('data-analytics-event');
            const label = el.getAttribute('data-analytics-label');
            trackEvent(event, { label });
        });
    });

    // Pricing toggle (monthly/annual)
    const toggle = document.querySelector('[data-pricing-toggle]');
    if (toggle) {
        let annual = false;
        toggle.addEventListener('click', () => {
            annual = !annual;
            document.querySelectorAll('[data-price-monthly]').forEach((el) => {
                el.textContent = annual ? el.getAttribute('data-price-annual') : el.getAttribute('data-price-monthly');
            });
            document.querySelectorAll('[data-period-monthly]').forEach((el) => {
                el.textContent = annual ? el.getAttribute('data-period-annual') : el.getAttribute('data-period-monthly');
            });
            document.querySelectorAll('[data-checkout-monthly]').forEach((el) => {
                const href = annual ? el.getAttribute('data-checkout-annual') : el.getAttribute('data-checkout-monthly');
                if (href) el.setAttribute('href', href);
            });
            trackEvent('pricing_toggle', { annual });
        });
    }

    // ROI calculator
    const roi = document.querySelector('[data-roi]');
    if (roi) {
        const hours = roi.querySelector('[data-roi-hours]');
        const rate = roi.querySelector('[data-roi-rate]');
        const result = roi.querySelector('[data-roi-result]');
        const compute = () => {
            const h = parseFloat(hours.value || '0');
            const r = parseFloat(rate.value || '0');
            const monthly = Math.round(h * r * 4);
            result.textContent = `$${monthly.toLocaleString()}`;
        };
        hours.addEventListener('input', compute);
        rate.addEventListener('input', compute);
        compute();
    }

    // Simple A/B test helper
    document.querySelectorAll('[data-abtest]').forEach((el) => {
        const variants = el.getAttribute('data-variants');
        if (!variants) return;
        const options = JSON.parse(variants);
        const pick = options[Math.floor(Math.random() * options.length)];
        if (pick?.text) el.textContent = pick.text;
        if (pick?.className) el.className = pick.className;
        trackEvent('ab_test', { id: el.getAttribute('data-abtest'), variant: pick?.id });
    });
});

// Scroll Animation Observer
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            observer.unobserve(entry.target); // Only animate once
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', () => {
    const revealedElements = document.querySelectorAll('.reveal');
    revealedElements.forEach(el => observer.observe(el));

    // Init Custom Cursor (works everywhere)
    // initCustomCursor(); // Disabled - no custom cursor following mouse

    // Init Spotlight
    import('./spotlight').then(module => module.initSpotlight()).catch(e => console.warn('Spotlight not loaded:', e));

    // Init Advanced Interactions
    import('./interactions').then(module => module.initInteractions()).catch(e => console.warn('Interactions not loaded:', e));

    // Init Advanced Effects
    import('./advanced-effects').then(module => {
        module.initAdvancedEffects();
        module.initEnhancedCardReveal();
    }).catch(e => console.warn('Advanced effects not loaded:', e));

    // Init Light Theme Effects
    import('./light-theme-effects').then(module => {
        module.initLightThemeEffects();
        module.watchThemeChanges();
    }).catch(e => console.warn('Light theme effects not loaded:', e));

    // ✨ Init Premium 3D Effects
    import('./effects-3d').then(module => {
        console.log('✨ Loading 3D Effects...');
        module.initAll3DEffects();
    }).catch(e => console.error('3D Effects failed to load:', e));

    // 🎆 Init Wow Effects
    import('./wow-effects').then(module => {
        module.initWowEffects();
    }).catch(e => console.warn('Wow effects not loaded:', e));
});

// Custom Cursor (works on all pages)
function initCustomCursor() {
    // Only on desktop
    if (window.innerWidth < 768) return;

    const cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    document.body.appendChild(cursor);

    const cursorGlow = document.createElement('div');
    cursorGlow.className = 'custom-cursor-glow';
    document.body.appendChild(cursorGlow);

    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;
    let glowX = 0, glowY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Smooth cursor follow
    function animateCursor() {
        cursorX += (mouseX - cursorX) * 0.2;
        cursorY += (mouseY - cursorY) * 0.2;

        glowX += (mouseX - glowX) * 0.1;
        glowY += (mouseY - glowY) * 0.1;

        cursor.style.transform = `translate(${cursorX}px, ${cursorY}px)`;
        cursorGlow.style.transform = `translate(${glowX}px, ${glowY}px)`;

        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Interactive states
    document.querySelectorAll('a, button, .btn, .card').forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.classList.add('cursor-hover');
            cursorGlow.classList.add('cursor-hover');
        });
        el.addEventListener('mouseleave', () => {
            cursor.classList.remove('cursor-hover');
            cursorGlow.classList.remove('cursor-hover');
        });
    });
}
