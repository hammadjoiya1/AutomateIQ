import './bootstrap';

import Alpine from 'alpinejs';
import Swup from 'swup';
import SwupScriptsPlugin from '@swup/scripts-plugin';
import SwupFormsPlugin from '@swup/forms-plugin';
import 'trix';
import 'trix/dist/trix.css';
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
const swup = new Swup({ plugins: [new SwupScriptsPlugin(), new SwupFormsPlugin()] });
window.swup = swup;



// Register Alpine components
initAnimatedCard();

Alpine.start();

// â”€â”€ Analytics â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
window.dataLayer = window.dataLayer || [];
function trackEvent(event, payload = {}) {
    window.dataLayer.push({ event, ...payload });
    document.dispatchEvent(new CustomEvent('analytics:event', { detail: { event, ...payload } }));
}
window.trackEvent = trackEvent;

// â”€â”€ Lenis smooth scroll â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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
    swup.hooks.on('visit:start', () => lenis.destroy());
}

// â”€â”€ Navbar GradientBlinds â€” mounted once, persists across Turbo navigations â”€â”€â”€
let navBlindsCleanup = null;
let driftingBlobsCleanup = null;
let physicsGridCleanup = null;
let terminalSimCleanup = null;
let spotlightCleanup = null;
let estimatorCleanup = null;

function initDriftingBlobs() {
    const canvas = document.getElementById('drifting-blobs-canvas');
    if (!canvas) {
        if (driftingBlobsCleanup) {
            driftingBlobsCleanup();
            driftingBlobsCleanup = null;
        }
        return;
    }

    if (driftingBlobsCleanup) return; // already running

    // Only run the drifting blobs in dark mode.
    // In light mode, leave the canvas transparent to avoid a muddy look.
    const isDark = document.body.getAttribute('data-theme') === 'dark';
    if (!isDark) {
        if (driftingBlobsCleanup) {
            driftingBlobsCleanup();
            driftingBlobsCleanup = null;
        }
        const ctx = canvas.getContext('2d');
        if (ctx) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        return;
    }

    // Read rim color from current theme token
    const accentHex = getComputedStyle(document.documentElement)
        .getPropertyValue('--color-accent').trim() || '#D4FF3D';

    function hexToRgba(hex, alpha) {
        const h = hex.replace('#', '');
        const r = parseInt(h.slice(0, 2), 16);
        const g = parseInt(h.slice(2, 4), 16);
        const b = parseInt(h.slice(4, 6), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    const primaryColor = hexToRgba(accentHex.startsWith('#') ? accentHex : '#D4FF3D', 0.15);
    const secondaryColor = 'rgba(255, 255, 255, 0.05)';

    import('./drifting-blobs').then(({ mountDriftingBlobs }) => {
        driftingBlobsCleanup = mountDriftingBlobs(canvas, { primaryColor, secondaryColor });
    }).catch((e) => {
        console.error('[drifting-blobs]', e);
    });
}

function initNavBlinds() {
    const mount = document.getElementById('nav-blinds-mount');
    if (!mount || navBlindsCleanup) return; // already running

    const getThemeColor = (varName, fallback) => {
        return getComputedStyle(document.documentElement).getPropertyValue(varName).trim() || fallback;
    };
    const accentColor = getThemeColor('--color-accent', '#D4FF3D');
    const hoverColor = getThemeColor('--primary-hover', '#BCDF35');

    import('./gradient-blinds').then(({ mountGradientBlinds }) => {
        navBlindsCleanup = mountGradientBlinds(mount, {
            gradientColors: [accentColor, hoverColor],
            angle: 15,
            noise: 0.22,
            blindCount: 12,
            blindMinWidth: 50,
            spotlightRadius: 0.6,
            spotlightSoftness: 1.2,
            spotlightOpacity: 0.0,
            mouseDampening: 0.12,
            shineDirection: 'left',
            mixBlendMode: 'normal',
        });
    }).catch((e) => {
        console.error('[nav-blinds]', e);
        throw e;
    });
}

// â”€â”€ Per-page init (runs on every Turbo navigation) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function initPage() {
    initLenis();
    initMagneticButtons();

    // Core motion system
    initButtonPress();
    initCardHover();
    initScrollReveal();
    initConnectorLines();


    // â”€â”€ Category & Section Scroll Reveals â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
            }
        });
    }, {
        threshold: 0.15
    });
    
    document.querySelectorAll('.section-scroll-fade, .category-scroll-fade').forEach(el => {
        sectionObserver.observe(el);
    });

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

    // Pricing toggle (monthly â†” annual)
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
        '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale, .count-up, .reveal, .hero-text-reveal'
    ).forEach((el) => scrollObserver.observe(el));

    // â”€â”€ Hero dashboard mockup scroll-tilt â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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

    // â”€â”€ Navbar GradientBlinds â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // initNavBlinds();
 
    // â”€â”€ Hero Drifting Blobs Background â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    initDriftingBlobs();

    // â”€â”€ Physics Grid Background Canvas â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const canvasGrid = document.getElementById('physics-canvas');
    if (canvasGrid) {
        if (physicsGridCleanup) {
            physicsGridCleanup();
            physicsGridCleanup = null;
        }
        import('./physics-grid').then(({ initPhysicsGrid }) => {
            physicsGridCleanup = initPhysicsGrid(canvasGrid);
        });
    }

    // â”€â”€ Terminal Mockup Emulator â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const terminalBody = document.querySelector('.terminal-body-sim');
    if (terminalBody) {
        if (terminalSimCleanup) {
            terminalSimCleanup();
            terminalSimCleanup = null;
        }
        import('./terminal-sim').then(({ initTerminalSim }) => {
            terminalSimCleanup = initTerminalSim(terminalBody);
        });
    }

    // â”€â”€ Mouse Border Spotlight Glowing Cards â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    if (document.querySelector('.spotlight-card')) {
        if (spotlightCleanup) {
            spotlightCleanup();
            spotlightCleanup = null;
        }
        import('./spotlight').then(({ initSpotlightCards }) => {
            spotlightCleanup = initSpotlightCards();
        });
    }

    // â”€â”€ Interactive Pipeline Cost Estimator â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    if (document.getElementById('estimator-volume')) {
        if (estimatorCleanup) {
            estimatorCleanup();
            estimatorCleanup = null;
        }
        import('./estimator').then(({ initEstimator }) => {
            estimatorCleanup = initEstimator();
        });
    }
}
 
swup.hooks.on('page:view', () => {
    Alpine.initTree(document.body);
});
 
swup.hooks.on('visit:start', () => {
    Alpine.destroyTree(document.body);
    if (driftingBlobsCleanup) {
        driftingBlobsCleanup();
        driftingBlobsCleanup = null;
    }
    if (physicsGridCleanup) {
        physicsGridCleanup();
        physicsGridCleanup = null;
    }
    if (terminalSimCleanup) {
        terminalSimCleanup();
        terminalSimCleanup = null;
    }
    if (spotlightCleanup) {
        spotlightCleanup();
        spotlightCleanup = null;
    }
    if (estimatorCleanup) {
        estimatorCleanup();
        estimatorCleanup = null;
    }
});

swup.hooks.on('visit:start', () => {
    document.body.classList.add('turbo-transitioning');
});

initPage();
swup.hooks.on('page:view', initPage);
