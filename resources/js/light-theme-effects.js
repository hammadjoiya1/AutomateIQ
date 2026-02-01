// Light Theme Advanced Effects

export function initLightThemeEffects() {
    // Only run on light theme - check body instead of html
    const theme = document.body.getAttribute('data-theme');
    if (theme !== 'light') return;

    console.log('Light theme advanced effects initialized');

    initFloatingParticles();
    initAnimatedGradientMesh();
    // initLightRays(); // Disabled - no mouse following
    initMorphingBlobs();
    initCardRipple();
}

// 1. Floating Particles System
function initFloatingParticles() {
    const particleContainer = document.createElement('div');
    particleContainer.className = 'light-particles';
    document.body.appendChild(particleContainer);

    const particleCount = 30;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'light-particle';

        // Random position
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        const size = Math.random() * 4 + 2;
        const duration = Math.random() * 20 + 15;
        const delay = Math.random() * 5;

        particle.style.left = `${x}%`;
        particle.style.top = `${y}%`;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `${delay}s`;

        particleContainer.appendChild(particle);
    }
}

// 2. Animated Gradient Mesh Background
function initAnimatedGradientMesh() {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    let hue = 200;

    function animateGradient() {
        hue = (hue + 0.1) % 360;

        const color1 = `hsl(${hue}, 70%, 95%)`;
        const color2 = `hsl(${(hue + 60) % 360}, 60%, 92%)`;
        const color3 = `hsl(${(hue + 120) % 360}, 50%, 90%)`;

        hero.style.background = `
            radial-gradient(at 20% 30%, ${color1} 0px, transparent 50%),
            radial-gradient(at 80% 20%, ${color2} 0px, transparent 50%),
            radial-gradient(at 50% 80%, ${color3} 0px, transparent 50%),
            linear-gradient(135deg, #fafafa 0%, #ffffff 100%)
        `;

        requestAnimationFrame(animateGradient);
    }

    animateGradient();
}

// 3. Interactive Light Rays
function initLightRays() {
    const rayContainer = document.createElement('div');
    rayContainer.className = 'light-rays';
    document.body.appendChild(rayContainer);

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    for (let i = 0; i < 5; i++) {
        const ray = document.createElement('div');
        ray.className = 'light-ray';
        ray.style.animationDelay = `${i * 0.3}s`;
        rayContainer.appendChild(ray);
    }

    function updateRays() {
        const rays = document.querySelectorAll('.light-ray');
        rays.forEach((ray, index) => {
            const angle = (mouseX / window.innerWidth) * 30 - 15 + (index * 5);
            ray.style.transform = `rotate(${angle}deg)`;
        });
        requestAnimationFrame(updateRays);
    }

    updateRays();
}

// 4. Morphing Blob Shapes
function initMorphingBlobs() {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    const blobContainer = document.createElement('div');
    blobContainer.className = 'blob-container';
    hero.appendChild(blobContainer);

    for (let i = 0; i < 3; i++) {
        const blob = document.createElement('div');
        blob.className = 'morphing-blob';
        blob.style.animationDelay = `${i * 2}s`;

        // Random position
        const x = 20 + Math.random() * 60;
        const y = 20 + Math.random() * 60;
        blob.style.left = `${x}%`;
        blob.style.top = `${y}%`;

        blobContainer.appendChild(blob);
    }
}

// 5. Card Ripple Effect
function initCardRipple() {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        card.addEventListener('mousedown', function (e) {
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            this.appendChild(ripple);

            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Initialize on theme change
export function watchThemeChanges() {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'data-theme') {
                const theme = document.body.getAttribute('data-theme');
                if (theme === 'light') {
                    // Clean up old effects
                    document.querySelectorAll('.light-particles, .light-rays, .blob-container').forEach(el => el.remove());
                    // Reinitialize
                    setTimeout(initLightThemeEffects, 100);
                } else {
                    // Clean up light theme effects
                    document.querySelectorAll('.light-particles, .light-rays, .blob-container').forEach(el => el.remove());
                }
            }
        });
    });

    // Watch body element instead of documentElement
    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
}
