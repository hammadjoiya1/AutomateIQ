/**
 * drifting-blobs.js
 * 2D canvas with two slowly drifting soft radial gradients.
 * Returns cleanup() for Turbo lifecycle.
 */

export function mountDriftingBlobs(canvas, opts = {}) {
    const ctx = canvas.getContext('2d');
    if (!ctx) return () => {};

    const {
        primaryColor = 'rgba(212, 255, 61, 0.15)', // Lime accent
        secondaryColor = 'rgba(255, 255, 255, 0.05)', // Muted secondary
        speed = 0.0005,
    } = opts;

    let raf = null;
    let width = 0;
    let height = 0;

    function resize() {
        const rect = canvas.getBoundingClientRect();
        width = rect.width;
        height = rect.height;
        // Adjust for high DPI displays
        const dpr = window.devicePixelRatio || 1;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        ctx.scale(dpr, dpr);
    }

    function drawBlob(x, y, radius, color) {
        const gradient = ctx.createRadialGradient(x, y, 0, x, y, radius);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, 'rgba(0, 0, 0, 0)'); // Fade out to transparent

        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(x, y, radius, 0, Math.PI * 2);
        ctx.fill();
    }

    function tick(ts) {
        raf = requestAnimationFrame(tick);
        
        ctx.clearRect(0, 0, width, height);
        
        const time = ts * speed;
        
        // Blob 1 (Primary - Lime)
        // Moves in a wider ellipse
        const b1X = width * 0.5 + Math.cos(time) * (width * 0.3);
        const b1Y = height * 0.5 + Math.sin(time * 0.8) * (height * 0.2);
        const b1Radius = Math.max(width, height) * 0.5;
        
        // Blob 2 (Secondary - Muted)
        // Moves in a slightly offset, different frequency path
        const b2X = width * 0.5 + Math.sin(time * 1.1) * (width * 0.25);
        const b2Y = height * 0.5 + Math.cos(time * 0.9) * (height * 0.3);
        const b2Radius = Math.max(width, height) * 0.6;

        // Draw with global composite operation for nice blending
        ctx.globalCompositeOperation = 'screen';
        
        drawBlob(b2X, b2Y, b2Radius, secondaryColor);
        drawBlob(b1X, b1Y, b1Radius, primaryColor);
        
        ctx.globalCompositeOperation = 'source-over';
    }

    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(canvas);

    raf = requestAnimationFrame(tick);

    return function cleanup() {
        if (raf) cancelAnimationFrame(raf);
        raf = null;
        ro.disconnect();
    };
}
