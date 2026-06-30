import './bootstrap';

import Alpine from 'alpinejs';
import * as Turbo from '@hotwired/turbo';
import { initAnimatedCard } from './AnimatedCard';
import { initGradientBlinds } from './GradientBlinds';
import { initMagneticButtons } from './magnetic';
import { initButtonPress, initCardHover, initScrollReveal, animateCount, initWaveform, initConnectorLines } from './motion-presets';

window.Alpine = Alpine;
window.Turbo = Turbo;

Turbo.config.drive.progressBarDelay = 150;

initAnimatedCard();
initGradientBlinds();

Alpine.start();

// Lightweight analytics event tracking
window.dataLayer = window.dataLayer || [];
function trackEvent(event, payload = {}) {
    window.dataLayer.push({ event, ...payload });
    document.dispatchEvent(new CustomEvent('analytics:event', { detail: { event, ...payload } }));
}
window.trackEvent = trackEvent;

function initLenis() {
    if (typeof Lenis !== 'undefined') {
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            orientation: 'vertical',
            gestureOrientation: 'vertical',
            smoothWheel: true,
            wheelMultiplier: 0.95,
            smoothTouch: false,
            infinite: false,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        document.addEventListener('turbo:before-visit', () => {
            lenis.destroy();
        }, { once: true });
    }
}

function initPage() {
    initLenis();
    initMagneticButtons();
    initButtonPress();
    initCardHover();
    initScrollReveal();
    initConnectorLines();
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

    // StratStudio Scroll Animation Observer
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                entry.target.classList.add('active'); // Keep support for old .reveal

                // If it's a count-up element, trigger count-up animation
                if (entry.target.classList.contains('count-up')) {
                    const targetVal = parseInt(entry.target.getAttribute('data-value') || '0', 10);
                    animateCount(entry.target, targetVal, 0.8);
                }

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Track scroll-reveal elements
    const revealedElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale, .count-up, .reveal');
    revealedElements.forEach(el => scrollObserver.observe(el));

    // Helper function to animate values
    function animateCountUp(obj, start, end, duration) {
        let startTimestamp = null;
        const prefix = obj.getAttribute('data-prefix') || '';
        const suffix = obj.getAttribute('data-suffix') || '';

        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            // Ease out quad
            const easeOutQuad = progress * (2 - progress);
            const current = Math.floor(easeOutQuad * (end - start) + start);
            obj.textContent = prefix + current.toLocaleString() + suffix;

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                obj.textContent = prefix + end.toLocaleString() + suffix;
            }
        };
        window.requestAnimationFrame(step);
    }

    // Scroll-bound 3D Dashboard Mockup Tilt
    const dashboard = document.querySelector('.dashboard-mockup-3d');
    if (dashboard) {
        const handleScrollTilt = () => {
            const rect = dashboard.getBoundingClientRect();
            const viewHeight = window.innerHeight;

            // Calculate how far through the viewport the dashboard is
            const elementTop = rect.top;
            const elementHeight = rect.height;

            // Progress from 0 (at bottom of screen) to 1 (fully visible / scrolled past)
            const progress = Math.min(Math.max((viewHeight - elementTop) / (viewHeight + elementHeight * 0.5), 0), 1);

            // Rotations go from 12deg tilt down to 0deg flat
            const rotX = Math.max(12 - (progress * 15), 0);
            const rotY = Math.max(-3 + (progress * 5), -3); // subtle Y rotation
            const scale = 0.96 + (progress * 0.04);

            dashboard.style.transform = `perspective(1200px) rotateX(${rotX}deg) rotateY(${rotY}deg) scale(${scale})`;
        };
        window.addEventListener('scroll', handleScrollTilt, { passive: true });
        handleScrollTilt(); // Run once initially
    }

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

    // Init waveform elements (idle ambient state by default)
    const waveformBars = [...document.querySelectorAll('.waveform-bar')];
    if (waveformBars.length) initWaveform(waveformBars, false);
    window.initWaveform = initWaveform;

    // 🌊 Init Interactive Glowy Waves (Hero Background)
    const glowyCanvas = document.getElementById('glowy-canvas');
    if (glowyCanvas) {
        import('./glowy-waves-hero').then(module => {
            let cleanup = module.initGlowyWavesHero('#glowy-canvas');

            // Cleanup on Turbo before-cache
            document.addEventListener('turbo:before-cache', () => {
                if (cleanup) cleanup();
            }, { once: true });
        }).catch(e => console.error('GlowyWavesHero failed to load:', e));
    }
}

document.addEventListener('turbo:load', () => {
    Alpine.initTree(document.body);
    initPage();
});

document.addEventListener('turbo:before-cache', () => {
    Alpine.destroyTree(document.body);
});

document.addEventListener('turbo:visit', () => {
    document.body.classList.add('turbo-loading');
});

document.addEventListener('turbo:render', () => {
    document.body.classList.remove('turbo-loading');
    document.body.classList.add('turbo-loaded');
    setTimeout(() => document.body.classList.remove('turbo-loaded'), 250);
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
