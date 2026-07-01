/**
 * physics-grid.js
 * Renders an interactive mesh of nodes and lines that warp under mouse influence using spring physics.
 */
export function initPhysicsGrid(canvas) {
    if (!canvas) return () => {};

    const ctx = canvas.getContext('2d');
    let width = 0;
    let height = 0;
    let nodes = [];
    const spacing = 45; // grid cell size
    const mouse = { x: -1000, y: -1000, active: false };
    
    // Physics parameters
    const stiffness = 0.04;
    const damping = 0.85;
    const repulsionRadius = 140;
    const repulsionStrength = 1.8;

    function resize() {
        const rect = canvas.getBoundingClientRect();
        width = rect.width;
        height = rect.height;
        canvas.width = width * (window.devicePixelRatio || 1);
        canvas.height = height * (window.devicePixelRatio || 1);
        ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
        
        // Regenerate grid nodes
        nodes = [];
        const cols = Math.ceil(width / spacing) + 1;
        const rows = Math.ceil(height / spacing) + 1;
        
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < cols; c++) {
                const x0 = c * spacing;
                const y0 = r * spacing;
                nodes.push({
                    x0, y0,       // home position
                    x: x0, y: y0, // current position
                    vx: 0, vy: 0, // velocity
                    row: r, col: c
                });
            }
        }
    }

    resize();
    window.addEventListener('resize', resize);

    function onMouseMove(e) {
        const rect = canvas.getBoundingClientRect();
        mouse.x = e.clientX - rect.left;
        mouse.y = e.clientY - rect.top;
        mouse.active = true;
    }

    function onMouseLeave() {
        mouse.active = false;
        mouse.x = -1000;
        mouse.y = -1000;
    }

    window.addEventListener('mousemove', onMouseMove, { passive: true });
    document.addEventListener('mouseleave', onMouseLeave);

    let running = true;
    let lastTime = 0;

    function loop(time) {
        if (!running) return;
        requestAnimationFrame(loop);
        
        ctx.clearRect(0, 0, width, height);

        // 1. Update Physics
        for (let i = 0; i < nodes.length; i++) {
            const node = nodes[i];
            
            // Spring force towards home position
            let ax = (node.x0 - node.x) * stiffness;
            let ay = (node.y0 - node.y) * stiffness;

            // Mouse repulsion force
            if (mouse.active) {
                const dx = node.x - mouse.x;
                const dy = node.y - mouse.y;
                const d = Math.sqrt(dx * dx + dy * dy);
                if (d < repulsionRadius && d > 0) {
                    const force = (1.0 - d / repulsionRadius) * repulsionStrength;
                    ax += (dx / d) * force;
                    ay += (dy / d) * force;
                }
            }

            node.vx = (node.vx + ax) * damping;
            node.vy = (node.vy + ay) * damping;
            node.x += node.vx;
            node.y += node.vy;
        }

        // 2. Render Mesh Lines
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.03)';
        ctx.lineWidth = 1;
        
        const cols = Math.ceil(width / spacing) + 1;
        
        for (let i = 0; i < nodes.length; i++) {
            const n = nodes[i];
            
            // Connect to right neighbor
            if (n.col < cols - 1 && i + 1 < nodes.length) {
                const right = nodes[i + 1];
                if (right.row === n.row) {
                    ctx.beginPath();
                    ctx.moveTo(n.x, n.y);
                    ctx.lineTo(right.x, right.y);
                    ctx.stroke();
                }
            }
            
            // Connect to bottom neighbor
            if (i + cols < nodes.length) {
                const bottom = nodes[i + cols];
                ctx.beginPath();
                ctx.moveTo(n.x, n.y);
                ctx.lineTo(bottom.x, bottom.y);
                ctx.stroke();
            }

            // Optional: Draw small nodes
            ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.beginPath();
            ctx.arc(n.x, n.y, 1.2, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    requestAnimationFrame(loop);

    return function cleanup() {
        running = false;
        window.removeEventListener('resize', resize);
        window.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseleave', onMouseLeave);
    };
}
