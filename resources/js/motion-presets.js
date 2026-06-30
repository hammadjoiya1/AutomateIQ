import { animate, inView } from "motion";

// Spring presets — define once, reuse everywhere
export const springs = {
    micro:   { type: "spring", stiffness: 500, damping: 30 }, // button press, toggle
    card:    { type: "spring", stiffness: 260, damping: 24 }, // hover lift, modal, dropdown
    ambient: { type: "spring", stiffness: 80,  damping: 20 }, // waveform idle, background drift
};

export const pageTransition = { duration: 0.15, easing: "ease-out" };

const reducedMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;

export function initButtonPress(selector = ".btn, [data-motion-press]") {
    document.querySelectorAll(selector).forEach(btn => {
        btn.addEventListener("pointerdown", () => {
            if (reducedMotion()) return;
            animate(btn, { scale: 0.96 }, springs.micro);
        });
        const release = () => {
            if (reducedMotion()) return;
            animate(btn, { scale: 1 }, springs.micro);
        };
        btn.addEventListener("pointerup", release);
        btn.addEventListener("pointerleave", release);
        btn.addEventListener("pointercancel", release);
    });
}

export function initCardHover(selector = ".card-interactive, .card-hover, [data-motion-card]") {
    document.querySelectorAll(selector).forEach(card => {
        const icon = card.querySelector("[data-card-icon]");
        card.addEventListener("pointerenter", () => {
            if (reducedMotion()) return;
            animate(card, { y: -4, boxShadow: "0 12px 24px rgba(0,0,0,0.4)" }, springs.card);
            if (icon) animate(icon, { rotate: 4 }, springs.micro);
        });
        card.addEventListener("pointerleave", () => {
            if (reducedMotion()) return;
            animate(card, { y: 0, boxShadow: "0 0px 0px rgba(0,0,0,0)" }, springs.card);
            if (icon) animate(icon, { rotate: 0 }, springs.micro);
        });
    });
}

export function initScrollReveal(selector = ".reveal") {
    if (reducedMotion()) {
        document.querySelectorAll(selector).forEach(el => {
            el.style.opacity = "1";
            el.style.transform = "none";
        });
        return;
    }
    inView(selector, ({ target }) => {
        animate(target, { opacity: [0, 1], y: [24, 0] }, { ...springs.card, delay: 0.05 });
    });
}

// Animate a number from 0 → to using the motion library
export function animateCount(el, to, duration = 0.8) {
    if (reducedMotion()) {
        el.textContent = to.toLocaleString();
        return;
    }
    animate((progress) => {
        el.textContent = Math.round(to * progress).toLocaleString();
    }, { duration, easing: "ease-out" });
}

// Workflow signal-chain connector lines — fill from top on scroll
export function initConnectorLines() {
    if (reducedMotion()) return;
    document.querySelectorAll('.workflow-connector-fill').forEach(fill => {
        fill.style.height = '0%';
        inView(fill.parentElement, () => {
            animate(fill, { height: ['0%', '100%'] }, { duration: 0.7, easing: 'ease-out', delay: 0.15 });
        }, { margin: '0px 0px -40px 0px' });
    });
    document.querySelectorAll('.workflow-node-dot').forEach((dot, i) => {
        inView(dot, () => {
            animate(dot, { scale: [0.6, 1.1, 1], opacity: [0, 1] },
                { duration: 0.35, delay: i * 0.05 });
        });
    });
}

// Waveform bars — idle (slow breathing) or active (fast, accent color)
export function initWaveform(bars, active = false) {
    if (reducedMotion()) return;
    if (active) {
        bars.forEach((bar, i) => {
            animate(bar, { scaleY: [0.4, 1, 0.5, 0.9, 0.3] },
                { duration: 0.6, repeat: Infinity, delay: i * 0.04 });
            bar.style.background = "var(--color-accent)";
        });
    } else {
        bars.forEach((bar, i) => {
            animate(bar, { scaleY: [0.3, 0.6, 0.3] },
                { ...springs.ambient, repeat: Infinity, delay: i * 0.08 });
        });
    }
}
