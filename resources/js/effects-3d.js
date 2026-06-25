/**
 * ✨ PREMIUM 3D EFFECTS ENGINE ✨
 * AutomateIQ - Next-Level Visual Experience
 */

// ═══════════════════════════════════════════════════════════════
// 3D TILT EFFECT - Apple-style card tilting
// ═══════════════════════════════════════════════════════════════
export function init3DTilt() {
    const tiltElements = document.querySelectorAll(
        "[data-tilt], .card-3d, .tilt-3d",
    );

    tiltElements.forEach((el) => {
        el.style.transformStyle = "preserve-3d";
        el.style.transition = "transform 0.1s ease-out";

        el.addEventListener("mousemove", (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Divide by larger number to make tilt less drastic
            const rotateX = (y - centerY) / 45;
            const rotateY = (centerX - x) / 45;

            el.style.transform = `
                perspective(1000px) 
                rotateX(${rotateX}deg) 
                rotateY(${rotateY}deg) 
                scale3d(1.01, 1.01, 1.01)
            `;

            // Inner glow effect
            const glowX = (x / rect.width) * 100;
            const glowY = (y / rect.height) * 100;
            el.style.background = `
                radial-gradient(
                    circle at ${glowX}% ${glowY}%, 
                    rgba(91, 33, 182, 0.15), 
                    transparent 50%
                ),
                ${getComputedStyle(el).backgroundColor || "white"}
            `;
        });

        el.addEventListener("mouseleave", () => {
            el.style.transform =
                "perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)";
            el.style.background = "";
        });
    });
}

// ═══════════════════════════════════════════════════════════════
// PARALLAX LAYERS - Depth-based scroll movement
// ═══════════════════════════════════════════════════════════════
export function initParallax() {
    const parallaxElements = document.querySelectorAll(
        "[data-parallax], .parallax",
    );

    window.addEventListener(
        "scroll",
        () => {
            const scrollY = window.pageYOffset;

            parallaxElements.forEach((el) => {
                const speed = parseFloat(el.dataset.speed) || 0.5;
                const direction = el.dataset.direction || "up";
                const yPos =
                    direction === "up" ? -(scrollY * speed) : scrollY * speed;

                el.style.transform = `translate3d(0, ${yPos}px, 0)`;
            });
        },
        { passive: true },
    );
}

// ═══════════════════════════════════════════════════════════════
// FLOATING 3D OBJECTS - Autonomous movement
// ═══════════════════════════════════════════════════════════════
export function initFloatingObjects() {
    const floaters = document.querySelectorAll("[data-float], .float-3d");

    floaters.forEach((el, index) => {
        const baseDelay = index * 0.5;
        const duration = 4 + Math.random() * 2;

        el.style.animation = `
            float3D ${duration}s ease-in-out ${baseDelay}s infinite,
            rotate3D ${duration * 2}s linear ${baseDelay}s infinite
        `;
    });

    // Add keyframes dynamically
    if (!document.getElementById("float3d-keyframes")) {
        const style = document.createElement("style");
        style.id = "float3d-keyframes";
        style.textContent = `
            @keyframes float3D {
                0%, 100% { transform: translateY(0) translateZ(0); }
                25% { transform: translateY(-15px) translateZ(10px); }
                50% { transform: translateY(-5px) translateZ(20px); }
                75% { transform: translateY(-20px) translateZ(5px); }
            }
            @keyframes rotate3D {
                0% { transform: rotateY(0deg) rotateX(0deg); }
                100% { transform: rotateY(360deg) rotateX(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
}

// ═══════════════════════════════════════════════════════════════
// MAGNETIC BUTTONS - Cursor attraction effect
// ═══════════════════════════════════════════════════════════════
export function initMagneticElements() {
    const magneticElements = document.querySelectorAll(
        "[data-magnetic], .btn-magnetic, .magnetic",
    );

    magneticElements.forEach((el) => {
        el.addEventListener("mousemove", (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            const strength = parseFloat(el.dataset.magneticStrength) || 0.3;

            el.style.transform = `translate(${x * strength}px, ${y * strength}px)`;
        });

        el.addEventListener("mouseleave", () => {
            el.style.transform = "translate(0, 0)";
            el.style.transition = "transform 0.3s ease-out";
        });

        el.addEventListener("mouseenter", () => {
            el.style.transition = "transform 0.1s ease-out";
        });
    });
}

// ═══════════════════════════════════════════════════════════════
// SCROLL-TRIGGERED ANIMATIONS - Elements animate on scroll
// ═══════════════════════════════════════════════════════════════
export function initScrollAnimations() {
    const animatedElements = document.querySelectorAll(
        "[data-scroll-animate], .scroll-animate",
    );

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const animation = el.dataset.animation || "fadeInUp";
                    const delay = el.dataset.delay || "0";

                    el.style.animationDelay = `${delay}ms`;
                    el.classList.add(`animate-${animation}`);
                    el.classList.add("animated");

                    observer.unobserve(el);
                }
            });
        },
        { threshold: 0.1, rootMargin: "0px 0px -50px 0px" },
    );

    animatedElements.forEach((el) => {
        el.style.opacity = "0";
        observer.observe(el);
    });
}

// ═══════════════════════════════════════════════════════════════
// MOUSE TRAIL EFFECT - Following particles
// ═══════════════════════════════════════════════════════════════
export function initMouseTrail() {
    if (window.innerWidth < 768) return; // Skip on mobile

    const trailContainer = document.createElement("div");
    trailContainer.className = "mouse-trail-container";
    trailContainer.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9999;
        overflow: hidden;
    `;
    document.body.appendChild(trailContainer);

    const particles = [];
    const particleCount = 15;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement("div");
        particle.style.cssText = `
            position: absolute;
            width: ${8 - i * 0.4}px;
            height: ${8 - i * 0.4}px;
            background: linear-gradient(135deg, rgba(91, 33, 182, ${0.6 - i * 0.04}), rgba(236, 72, 153, ${0.4 - i * 0.02}));
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
        `;
        trailContainer.appendChild(particle);
        particles.push({ el: particle, x: 0, y: 0 });
    }

    let mouseX = 0,
        mouseY = 0;

    document.addEventListener("mousemove", (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateTrail() {
        let x = mouseX;
        let y = mouseY;

        particles.forEach((particle, index) => {
            const nextX = x + (particle.x - x) * 0.3;
            const nextY = y + (particle.y - y) * 0.3;

            particle.x = nextX;
            particle.y = nextY;

            particle.el.style.left = `${nextX}px`;
            particle.el.style.top = `${nextY}px`;

            x = nextX;
            y = nextY;
        });

        requestAnimationFrame(animateTrail);
    }

    animateTrail();
}

// ═══════════════════════════════════════════════════════════════
// SCROLL PROGRESS INDICATOR
// ═══════════════════════════════════════════════════════════════
export function initScrollProgress() {
    const progressBar = document.createElement("div");
    progressBar.className = "scroll-progress-bar";
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #5B21B6, #EC4899, #7C3AED);
        z-index: 9999;
        transition: width 0.1s ease-out;
        box-shadow: 0 0 10px rgba(91, 33, 182, 0.5);
    `;
    document.body.appendChild(progressBar);

    window.addEventListener(
        "scroll",
        () => {
            const scrollTop = window.pageYOffset;
            const docHeight =
                document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            progressBar.style.width = `${scrollPercent}%`;
        },
        { passive: true },
    );
}

// ═══════════════════════════════════════════════════════════════
// REVEAL ON SCROLL - Staggered entrance
// ═══════════════════════════════════════════════════════════════
export function initStaggeredReveal() {
    const revealGroups = document.querySelectorAll("[data-stagger-reveal]");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const children = entry.target.children;
                    Array.from(children).forEach((child) => {
                        child.style.opacity = "0";
                        child.style.transform = "translateY(10px)";
                        child.style.transition = `all 0.2s cubic-bezier(0.16, 1, 0.3, 1) 0ms`;

                        child.style.opacity = "1";
                        child.style.transform = "translateY(0)";
                    });
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 },
    );

    revealGroups.forEach((group) => observer.observe(group));
}

// ═══════════════════════════════════════════════════════════════
// TEXT SCRAMBLE EFFECT WITH RANDOM WORDS
// ═══════════════════════════════════════════════════════════════
export function initTextScramble() {
    const scrambleElements = document.querySelectorAll("[data-scramble]");

    // Random words to cycle through
    const randomWords = [
        "Innovation",
        "Creativity",
        "Automation",
        "Revolution",
        "Viral Content",
        "AI Powered",
        "Game Changer",
        "Next Level",
        "Pro Creator",
        "Go Viral",
        "Scale Fast",
        "Smart Tools",
    ];

    class TextScramble {
        constructor(el) {
            this.el = el;
            this.originalText = el.textContent;
            this.hasBlur = el.hasAttribute("data-scramble-blur");
        }

        scramble() {
            let wordIndex = 0;
            const totalCycles = 8; // Number of random words to show

            // Start with blur if enabled
            if (this.hasBlur) {
                this.el.style.filter = "blur(6px)";
                this.el.style.transition = "filter 0.2s ease-out";
            }

            const interval = setInterval(() => {
                // Show random word
                this.el.textContent =
                    randomWords[Math.floor(Math.random() * randomWords.length)];
                wordIndex++;

                // Gradually reduce blur
                if (this.hasBlur) {
                    const progress = wordIndex / totalCycles;
                    const blurAmount = Math.max(0, 6 - progress * 8);
                    this.el.style.filter = `blur(${blurAmount}px)`;
                }

                if (wordIndex >= totalCycles) {
                    clearInterval(interval);
                    // Final reveal of actual text
                    this.el.textContent = this.originalText;
                    this.el.style.filter = "blur(0)";
                }
            }, 100); // Fast word cycling
        }
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const scrambler = new TextScramble(entry.target);
                setTimeout(() => scrambler.scramble(), 300);
                observer.unobserve(entry.target);
            }
        });
    });

    scrambleElements.forEach((el) => observer.observe(el));
}

// ═══════════════════════════════════════════════════════════════
// HERO 3D SCENE - Moving shapes in background
// ═══════════════════════════════════════════════════════════════
export function initHero3DScene() {
    const heroSection = document.querySelector(".hero, [data-hero-3d]");
    if (!heroSection) return;

    // Create 3D scene container
    const scene = document.createElement("div");
    scene.className = "hero-3d-scene";
    scene.style.cssText = `
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    `;
    heroSection.style.position = "relative";
    heroSection.insertBefore(scene, heroSection.firstChild);

    // Create floating 3D shapes
    const shapes = [
        { type: "sphere", size: 80, x: "10%", y: "20%", delay: 0 },
        { type: "cube", size: 60, x: "85%", y: "30%", delay: 0.5 },
        { type: "torus", size: 100, x: "75%", y: "70%", delay: 1 },
        { type: "sphere", size: 40, x: "20%", y: "75%", delay: 1.5 },
        { type: "cube", size: 50, x: "50%", y: "15%", delay: 2 },
    ];

    shapes.forEach((shape) => {
        const el = document.createElement("div");
        el.className = `hero-shape shape-${shape.type}`;
        el.style.cssText = `
            position: absolute;
            left: ${shape.x};
            top: ${shape.y};
            width: ${shape.size}px;
            height: ${shape.size}px;
            background: linear-gradient(135deg, rgba(91, 33, 182, 0.2), rgba(236, 72, 153, 0.15));
            border: 1px solid rgba(91, 33, 182, 0.2);
            border-radius: ${shape.type === "sphere" ? "50%" : shape.type === "torus" ? "50%" : "12px"};
            animation: heroFloat${Math.floor(Math.random() * 3)} 8s ease-in-out ${shape.delay}s infinite;
            backdrop-filter: blur(5px);
        `;
        scene.appendChild(el);
    });

    // Add keyframes
    const style = document.createElement("style");
    style.textContent = `
        @keyframes heroFloat0 {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        @keyframes heroFloat1 {
            0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); }
            50% { transform: translateY(-20px) translateX(20px) rotate(90deg); }
        }
        @keyframes heroFloat2 {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-25px) scale(1.1); }
        }
    `;
    document.head.appendChild(style);

    // Mouse parallax for shapes - using margin offset to avoid transform conflict
    let basePositions = [];
    scene.querySelectorAll(".hero-shape").forEach((shape, i) => {
        basePositions[i] = {
            left: parseFloat(shape.style.left),
            top: parseFloat(shape.style.top),
        };
    });

    document.addEventListener("mousemove", (e) => {
        const rect = heroSection.getBoundingClientRect();

        // Only respond when mouse is near hero section
        if (e.clientY > rect.bottom + 200) return;

        const x = (e.clientX / window.innerWidth - 0.5) * 2;
        const y = (e.clientY / window.innerHeight - 0.5) * 2;

        scene.querySelectorAll(".hero-shape").forEach((shape, i) => {
            const depth = (i + 1) * 20; // Increased depth for more movement
            shape.style.marginLeft = `${x * depth}px`;
            shape.style.marginTop = `${y * depth}px`;
        });
    });
}

// ═══════════════════════════════════════════════════════════════
// SMOOTH SCROLL SECTIONS
// ═══════════════════════════════════════════════════════════════
export function initSmoothSections() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });

    document.querySelectorAll("a, button").forEach((el) => {
        el.addEventListener("touchstart", () => {}, { passive: true });
    });
}

// ═══════════════════════════════════════════════════════════════
// INITIALIZE ALL EFFECTS
// ═══════════════════════════════════════════════════════════════
export function initAll3DEffects() {
    init3DTilt();
    initParallax();
    initFloatingObjects();
    // initMagneticElements(); // Disabled - buttons don't move with cursor
    initScrollAnimations();
    // initMouseTrail(); // Disabled - no particles following cursor
    initScrollProgress();
    initStaggeredReveal();
    initTextScramble();
    // initHero3DScene(); // Disabled - no 3D shapes following cursor
    initSmoothSections();

    console.log("✨ Premium 3D Effects initialized");
}
