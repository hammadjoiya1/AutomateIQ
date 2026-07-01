import './bootstrap';

import Alpine from 'alpinejs';
import * as Turbo from '@hotwired/turbo';
import { initAnimatedCard } from './AnimatedCard';
import { initMagneticButtons } from './magnetic';
import {
    initButtonPress,
    initCardHover,
    initScrollReveal,
    animateCount,
    initWaveform,
    initConnectorLines,
} from './motion-presets';

window.Alpine = Alpine;
window.Turbo = Turbo;

Turbo.config.drive.progressBarDelay = 150;

// Register Alpine components
initAnimatedCard();

Alpine.start();

// ── Analytics ─────────────────────────────────────────────────────────────────
window.dataLayer = window.dataLayer || [];
function trackEvent(event, payload = {}) {
    window.dataLayer.push({ event, ...payload });
    document.dispatchEvent(new CustomEvent('analytics:event', { detail: { event, ...payload } }));
}
window.trackEvent = trackEvent;

// ── Lenis smooth scroll ────────────────────────────────────────────────────────
function initLenis() {
    if (typeof Lenis === 'undefined') return;
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        wheelMultiplier: 0.95,
        smoothTouch: false,
    });
    const raf = (time) => { lenis.raf(time); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);
    document.addEventListener('turbo:before-visit', () => lenis.destroy(), { once: true });
}

// ── Navbar GradientBlinds — mounted once, persists across Turbo navigations ───
let navBlindsCleanup = null;
let heroBlindsCleanup = null;

function initHeroBlinds() {
    const mount = document.getElementById('hero-blinds-mount');
    if (!mount) {
        if (heroBlindsCleanup) {
            heroBlindsCleanup();
            heroBlindsCleanup = null;
        }
        return;
    }

    if (heroBlindsCleanup) return; // already running

    import('./gradient-blinds').then(({ mountGradientBlinds }) => {
        heroBlindsCleanup = mountGradientBlinds(mount, {
            gradientColors: ['#FF9FFC', '#5227FF'],
            angle: 0,
            noise: 0.3,
            blindCount: 12,
            blindMinWidth: 50,
            spotlightRadius: 0.5,
            spotlightSoftness: 1,
            spotlightOpacity: 1,
            mouseDampening: 0.15,
            distortAmount: 0,
            shineDirection: 'left',
            mixBlendMode: 'lighten',
        });
    }).catch((e) => {
        console.error('[hero-blinds]', e);
        throw e;
    });
}

function initNavBlinds() {
    const mount = document.getElementById('nav-blinds-mount');
    if (!mount || navBlindsCleanup) return; // already running

    import('./gradient-blinds').then(({ mountGradientBlinds }) => {
        navBlindsCleanup = mountGradientBlinds(mount, {
            gradientColors: ['#a855f7', '#7c3aed', '#4f46e5'],
            angle: 15,
            noise: 0.22,
            blindCount: 12,
            blindMinWidth: 50,
            spotlightRadius: 0.6,
            spotlightSoftness: 1.2,
            spotlightOpacity: 0.9,
            mouseDampening: 0.12,
            shineDirection: 'left',
        });
    }).catch((e) => {
        console.error('[nav-blinds]', e);
        throw e;
    });
}

// ── Per-page init (runs on every Turbo navigation) ────────────────────────────
function initPage() {
    initLenis();
    initMagneticButtons();

    // Core motion system
    initButtonPress();
    initCardHover();
    initScrollReveal();
    initConnectorLines();

    // Waveform bars
    document.querySelectorAll('.waveform').forEach((wf) => {
        const bars = [...wf.querySelectorAll('.waveform-bar')];
        if (bars.length) initWaveform(bars, wf.dataset.waveform === 'active');
    });
    window.initWaveform = initWaveform;

    trackEvent('page_view', { path: window.location.pathname });

    // Analytics click events
    document.querySelectorAll('[data-analytics-event]').forEach((el) => {
        el.addEventListener('click', () => {
            trackEvent(
                el.getAttribute('data-analytics-event'),
                { label: el.getAttribute('data-analytics-label') }
            );
        });
    });

    // Pricing toggle (monthly ↔ annual)
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
        const hours  = roi.querySelector('[data-roi-hours]');
        const rate   = roi.querySelector('[data-roi-rate]');
        const result = roi.querySelector('[data-roi-result]');
        const compute = () => {
            result.textContent = `$${Math.round(parseFloat(hours.value || 0) * parseFloat(rate.value || 0) * 4).toLocaleString()}`;
        };
        hours.addEventListener('input', compute);
        rate.addEventListener('input', compute);
        compute();
    }

    // A/B test helper
    document.querySelectorAll('[data-abtest]').forEach((el) => {
        const variants = el.getAttribute('data-variants');
        if (!variants) return;
        const options = JSON.parse(variants);
        const pick = options[Math.floor(Math.random() * options.length)];
        if (pick?.text) el.textContent = pick.text;
        if (pick?.className) el.className = pick.className;
        trackEvent('ab_test', { id: el.getAttribute('data-abtest'), variant: pick?.id });
    });

    // IntersectionObserver for scroll-reveal + count-up
    const scrollObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible', 'active');
            if (entry.target.classList.contains('count-up')) {
                const prefix = entry.target.getAttribute('data-prefix') || '';
                const suffix = entry.target.getAttribute('data-suffix') || '';
                const to = parseInt(entry.target.getAttribute('data-value') || '0', 10);
                animateCount(entry.target, to, 0.8, prefix, suffix);
            }
            obs.unobserve(entry.target);
        });
    }, { rootMargin: '0px', threshold: 0.1 });

    document.querySelectorAll(
        '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale, .count-up, .reveal'
    ).forEach((el) => scrollObserver.observe(el));

    // ── Hero dashboard mockup scroll-tilt ────────────────────────────────────
    const dashboard = document.querySelector('.dashboard-mockup-3d');
    if (dashboard) {
        const handleScrollTilt = () => {
            const rect     = dashboard.getBoundingClientRect();
            const progress = Math.min(Math.max((window.innerHeight - rect.top) / (window.innerHeight + rect.height * 0.5), 0), 1);
            const rotX     = Math.max(12 - progress * 15, 0);
            const rotY     = Math.max(-3 + progress * 5, -3);
            dashboard.style.transform = `perspective(1200px) rotateX(${rotX}deg) rotateY(${rotY}deg) scale(${0.96 + progress * 0.04})`;
        };
        window.addEventListener('scroll', handleScrollTilt, { passive: true });
        handleScrollTilt();
    }

    // ── Navbar GradientBlinds ─────────────────────────────────────────────────
    initNavBlinds();

    // ── Hero GradientBlinds ───────────────────────────────────────────────────
    initHeroBlinds();
}

document.addEventListener('turbo:load', () => {
    Alpine.initTree(document.body);
    initPage();
});

document.addEventListener('turbo:before-cache', () => {
    Alpine.destroyTree(document.body);
    if (heroBlindsCleanup) {
        heroBlindsCleanup();
        heroBlindsCleanup = null;
    }
});

document.addEventListener('turbo:visit', () => {
    document.body.classList.add('turbo-transitioning');
});

document.addEventListener('turbo:render', () => {
    document.body.classList.remove('turbo-transitioning');
});
