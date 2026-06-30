export function initGlowyWavesHero(canvasSelector) {
    const canvas = document.querySelector(canvasSelector);
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    let animationId;
    let time = 0;

    let mouse = { x: 0, y: 0 };
    let targetMouse = { x: 0, y: 0 };

    const computeThemeColors = () => {
        const rootStyles = getComputedStyle(document.documentElement);

        const resolveColor = (variables, alpha = 1) => {
            const tempEl = document.createElement("div");
            tempEl.style.position = "absolute";
            tempEl.style.visibility = "hidden";
            tempEl.style.width = "1px";
            tempEl.style.height = "1px";
            document.body.appendChild(tempEl);

            let color = `rgba(255, 255, 255, ${alpha})`;

            for (const variable of variables) {
                const value = rootStyles.getPropertyValue(variable).trim();
                if (value) {
                    tempEl.style.backgroundColor = `var(${variable})`;
                    const computedColor = getComputedStyle(tempEl).backgroundColor;

                    if (computedColor && computedColor !== "rgba(0, 0, 0, 0)") {
                        if (alpha < 1) {
                            const rgbMatch = computedColor.match(
                                /rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*[\d.]+)?\)/
                            );
                            if (rgbMatch) {
                                color = `rgba(${rgbMatch[1]}, ${rgbMatch[2]}, ${rgbMatch[3]}, ${alpha})`;
                            } else {
                                color = computedColor;
                            }
                        } else {
                            color = computedColor;
                        }
                        break;
                    }
                }
            }

            document.body.removeChild(tempEl);
            return color;
        };

        // Fallbacks if CSS vars don't resolve
        return {
            backgroundTop: resolveColor(["--bg"], 1) !== 'rgba(255, 255, 255, 1)' ? resolveColor(["--bg"], 1) : '#0b1121',
            backgroundBottom: resolveColor(["--bg-2", "--bg"], 0.95),
            wavePalette: [
                { offset: 0, amplitude: 70, frequency: 0.003, color: resolveColor(["--primary"], 0.8), opacity: 0.45 },
                { offset: Math.PI / 2, amplitude: 90, frequency: 0.0026, color: resolveColor(["--accent", "--primary"], 0.7), opacity: 0.35 },
                { offset: Math.PI, amplitude: 60, frequency: 0.0034, color: resolveColor(["--secondary", "--text"], 0.65), opacity: 0.3 },
                { offset: Math.PI * 1.5, amplitude: 80, frequency: 0.0022, color: resolveColor(["--primary-light", "--text"], 0.25), opacity: 0.25 },
                { offset: Math.PI * 2, amplitude: 55, frequency: 0.004, color: resolveColor(["--text"], 0.2), opacity: 0.2 },
            ],
        };
    };

    let themeColors = computeThemeColors();

    const handleThemeMutation = () => {
        themeColors = computeThemeColors();
    };

    const observer = new MutationObserver(handleThemeMutation);
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ["class", "data-theme"],
    });

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const mouseInfluence = prefersReducedMotion ? 10 : 70;
    const influenceRadius = prefersReducedMotion ? 160 : 320;
    const smoothing = prefersReducedMotion ? 0.04 : 0.1;

    const resizeCanvas = () => {
        const parent = canvas.parentElement;
        canvas.width = parent ? parent.offsetWidth : window.innerWidth;
        canvas.height = parent ? parent.offsetHeight : window.innerHeight;
    };

    const recenterMouse = () => {
        const centerPoint = { x: canvas.width / 2, y: canvas.height / 2 };
        mouse = { ...centerPoint };
        targetMouse = { ...centerPoint };
    };

    const handleResize = () => {
        resizeCanvas();
        recenterMouse();
    };

    const handleMouseMove = (event) => {
        const rect = canvas.getBoundingClientRect();
        targetMouse = {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top
        };
    };

    const handleMouseLeave = () => {
        recenterMouse();
    };

    resizeCanvas();
    recenterMouse();

    window.addEventListener("resize", handleResize);
    canvas.addEventListener("mousemove", handleMouseMove);
    canvas.addEventListener("mouseleave", handleMouseLeave);

    const drawWave = (wave) => {
        ctx.save();
        ctx.fillStyle = wave.color;
        ctx.globalAlpha = wave.opacity;
        ctx.shadowBlur = 20;
        ctx.shadowColor = wave.color;

        const dotSpacing = 16;
        for (let x = 0; x <= canvas.width; x += dotSpacing) {
            const dx = x - mouse.x;
            const dy = canvas.height / 2 - mouse.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            const influence = Math.max(0, 1 - distance / influenceRadius);
            const mouseEffect = influence * mouseInfluence * Math.sin(time * 0.001 + x * 0.01 + wave.offset);

            const y = canvas.height / 2 +
                Math.sin(x * wave.frequency + time * 0.002 + wave.offset) * wave.amplitude +
                Math.sin(x * wave.frequency * 0.4 + time * 0.003) * (wave.amplitude * 0.45) +
                mouseEffect;

            ctx.beginPath();
            ctx.arc(x, y, 1.8, 0, Math.PI * 2);
            ctx.fill();
        }

        ctx.restore();
    };

    const animate = () => {
        time += 1;

        mouse.x += (targetMouse.x - mouse.x) * smoothing;
        mouse.y += (targetMouse.y - mouse.y) * smoothing;

        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, themeColors.backgroundTop);
        gradient.addColorStop(1, themeColors.backgroundBottom);

        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.globalAlpha = 1;
        ctx.shadowBlur = 0;

        themeColors.wavePalette.forEach(drawWave);

        animationId = window.requestAnimationFrame(animate);
    };

    animationId = window.requestAnimationFrame(animate);

    // Return a cleanup function
    return () => {
        window.removeEventListener("resize", handleResize);
        canvas.removeEventListener("mousemove", handleMouseMove);
        canvas.removeEventListener("mouseleave", handleMouseLeave);
        cancelAnimationFrame(animationId);
        observer.disconnect();
    };
}
