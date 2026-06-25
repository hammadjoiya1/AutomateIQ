// Advanced Interactive Features Module

export function initAdvancedEffects() {
    console.log('Advanced effects initialized');

    initScrollProgress();
    initAdvancedScrollReveal();
    // Cursor is now initialized in app.js globally
    // initMouseGradient(); // Disabled - no mouse following effects
}

// 1. Scroll Progress Indicator
function initScrollProgress() {
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', () => {
        const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.pageYOffset / windowHeight) * 100;
        progressBar.style.width = `${scrolled}%`;
    }, { passive: true });
}

// 2. Advanced Scroll Reveal with Intersection Observer
function initAdvancedScrollReveal() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add stagger delay based on index
                setTimeout(() => {
                    entry.target.classList.add('revealed');
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements with reveal class
    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

// 3. Custom Cursor Effect
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

    // Smooth cursor follow with requestAnimationFrame
    function animateCursor() {
        // Cursor follows immediately
        cursorX += (mouseX - cursorX) * 0.2;
        cursorY += (mouseY - cursorY) * 0.2;

        // Glow follows with more lag for trailing effect
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

// 4. Mouse-following Background Gradient
function initMouseGradient() {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    let mouseX = 0, mouseY = 0;

    hero.addEventListener('mousemove', (e) => {
        const rect = hero.getBoundingClientRect();
        mouseX = ((e.clientX - rect.left) / rect.width) * 100;
        mouseY = ((e.clientY - rect.top) / rect.height) * 100;

        hero.style.setProperty('--mouse-x-percent', `${mouseX}%`);
        hero.style.setProperty('--mouse-y-percent', `${mouseY}%`);
    });
}

// 5. Enhanced Reveal Animation for Cards
export function initEnhancedCardReveal() {
    const cards = document.querySelectorAll('.card');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0, rootMargin: '200px 0px' });

    cards.forEach((card) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';
        card.style.transition = 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1) 0s';
        observer.observe(card);
    });
}
