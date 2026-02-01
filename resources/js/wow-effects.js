/**
 * ✨ WOW EFFECTS - Extra Visual Impact ✨
 */

// Particle burst on button click
export function initParticleBurst() {
    document.querySelectorAll('.btn-primary, .btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            createParticleBurst(e.clientX, e.clientY);
        });
    });
}

function createParticleBurst(x, y) {
    const colors = ['#5B21B6', '#7C3AED', '#EC4899', '#A855F7', '#8B5CF6'];
    const particleCount = 25;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            left: ${x}px;
            top: ${y}px;
            width: 10px;
            height: 10px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
        `;
        document.body.appendChild(particle);

        const angle = (Math.PI * 2 / particleCount) * i;
        const velocity = 5 + Math.random() * 10;
        const vx = Math.cos(angle) * velocity;
        const vy = Math.sin(angle) * velocity;

        let posX = 0, posY = 0, opacity = 1, scale = 1;

        function animate() {
            posX += vx;
            posY += vy + 2; // gravity
            opacity -= 0.02;
            scale -= 0.01;

            particle.style.transform = `translate(${posX}px, ${posY}px) scale(${Math.max(0, scale)})`;
            particle.style.opacity = opacity;

            if (opacity > 0) {
                requestAnimationFrame(animate);
            } else {
                particle.remove();
            }
        }
        requestAnimationFrame(animate);
    }
}

// Ripple effect on buttons
export function initRippleEffect() {
    document.querySelectorAll('.btn, button').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';

        btn.addEventListener('click', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                left: ${x}px;
                top: ${y}px;
                width: 0;
                height: 0;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                transform: translate(-50%, -50%);
                animation: rippleExpand 0.6s ease-out forwards;
                pointer-events: none;
            `;
            btn.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add keyframe
    if (!document.getElementById('ripple-keyframe')) {
        const style = document.createElement('style');
        style.id = 'ripple-keyframe';
        style.textContent = `
            @keyframes rippleExpand {
                to {
                    width: 400px;
                    height: 400px;
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Typewriter effect for headings
export function initTypewriter() {
    const typewriterElements = document.querySelectorAll('[data-typewriter]');

    typewriterElements.forEach(el => {
        const text = el.textContent;
        el.textContent = '';
        el.style.borderRight = '2px solid #5B21B6';

        let i = 0;
        function type() {
            if (i < text.length) {
                el.textContent += text.charAt(i);
                i++;
                setTimeout(type, 50 + Math.random() * 50);
            } else {
                el.style.borderRight = 'none';
            }
        }

        // Start when visible
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                type();
                observer.disconnect();
            }
        });
        observer.observe(el);
    });
}

// Counter animation for stats
export function initCounterAnimation() {
    const counters = document.querySelectorAll('[data-counter]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.dataset.counter);
                const duration = 2000;
                const startTime = performance.now();

                function update(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3); // Ease out cubic

                    el.textContent = Math.floor(eased * target).toLocaleString();

                    if (progress < 1) {
                        requestAnimationFrame(update);
                    } else {
                        el.textContent = target.toLocaleString();
                    }
                }

                requestAnimationFrame(update);
                observer.unobserve(el);
            }
        });
    });

    counters.forEach(el => observer.observe(el));
}

// Spotlight cursor effect
export function initSpotlightCursor() {
    if (window.innerWidth < 768) return;

    const spotlight = document.createElement('div');
    spotlight.style.cssText = `
        position: fixed;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(91, 33, 182, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 9998;
        transition: transform 0.1s ease-out;
        transform: translate(-50%, -50%);
    `;
    document.body.appendChild(spotlight);

    document.addEventListener('mousemove', (e) => {
        spotlight.style.left = e.clientX + 'px';
        spotlight.style.top = e.clientY + 'px';
    });
}

// Initialize all wow effects
export function initWowEffects() {
    initParticleBurst();
    initRippleEffect();
    initTypewriter();
    initCounterAnimation();
    // initSpotlightCursor(); // Disabled - no cursor spotlight
    console.log('🎆 Wow Effects initialized!');
}
