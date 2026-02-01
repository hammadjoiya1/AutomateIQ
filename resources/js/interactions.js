// Advanced 3D and Interactive Effects

export function initInteractions() {
    console.log('Advanced interactions initialized');

    // 1. 3D Tilt Effect on Cards - DISABLED per user request
    // initCardTilt();

    // 2. Magnetic Buttons - DISABLED per user request
    // initMagneticButtons();

    // 3. Parallax Scrolling
    initParallax();

    // 4. Smooth Scroll
    initSmoothScroll();
}

// 3D Tilt Effect - Cards tilt based on mouse position
function initCardTilt() {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 10; // Reduced intensity
            const rotateY = (centerX - x) / 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            card.style.transition = 'transform 0.1s ease';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
            card.style.transition = 'transform 0.5s ease';
        });
    });
}

// Magnetic Buttons - Buttons attract to cursor
function initMagneticButtons() {
    const buttons = document.querySelectorAll('.btn-primary, .magnetic-btn');

    buttons.forEach(button => {
        button.addEventListener('mousemove', (e) => {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            // Only attract if within reasonable distance
            const distance = Math.sqrt(x * x + y * y);
            const maxDistance = 80;

            if (distance < maxDistance) {
                const strength = 0.3;
                button.style.transform = `translate(${x * strength}px, ${y * strength}px) scale(1.05)`;
            }
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translate(0, 0) scale(1)';
        });
    });
}

// Parallax Scrolling
function initParallax() {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = hero.querySelectorAll('[data-parallax]');

        parallaxElements.forEach(el => {
            const speed = el.dataset.parallax || 0.5;
            el.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
}

// Smooth Scroll
function initSmoothScroll() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Fix iOS/Safari double-tap by ensuring touchstart doesn't get blocked
    document.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('touchstart', () => { }, { passive: true });
    });
}
