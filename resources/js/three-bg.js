/**
 * three-bg.js — Premium 3D WebGL Background
 *
 * Layers:
 *   1. Rippling particle grid (brand-coloured)
 *   2. Depth star field (parallax)
 *   3. Wireframe icosahedra (slowly orbiting)
 *   4. Torus knot accent (subtle depth)
 *   5. Mouse parallax across all layers
 *
 * Requires Three.js on window.THREE (loaded via CDN).
 * Returns a cleanup function safe for Turbo navigation.
 */
export function initThreeBg(canvasId = 'three-bg-canvas') {
    // Give Three.js CDN a moment to register on window if this runs early
    const THREE = window.THREE;
    if (!THREE) {
        console.warn('[three-bg] window.THREE not found — skipping 3D background.');
        return null;
    }

    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        console.warn('[three-bg] Canvas #' + canvasId + ' not found.');
        return null;
    }

    /* ── Renderer ──────────────────────────────────────────── */
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    const scene = new THREE.Scene();

    /* ── Camera ────────────────────────────────────────────── */
    const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 1000);
    camera.position.set(0, 0, 28);

    /* ── Colours ───────────────────────────────────────────── */
    const C_PRIMARY   = new THREE.Color('#ff4b1f');
    const C_SECONDARY = new THREE.Color('#ff8c42');

    /* ─────────────────────────────────────────────────────────
       LAYER 1 — Rippling particle grid
    ───────────────────────────────────────────────────────── */
    const GRID = 44;
    const STEP = 1.1;
    const count = GRID * GRID;
    const posArr  = new Float32Array(count * 3);
    const colArr  = new Float32Array(count * 3);
    const phases  = new Float32Array(count);
    const half    = (GRID - 1) * STEP * 0.5;

    for (let i = 0; i < GRID; i++) {
        for (let j = 0; j < GRID; j++) {
            const idx = i * GRID + j;
            posArr[idx * 3]     = i * STEP - half;
            posArr[idx * 3 + 1] = j * STEP - half;
            posArr[idx * 3 + 2] = 0;
            phases[idx] = (i + j) * 0.22;

            const t = (i + j) / (GRID * 2);
            const c = C_PRIMARY.clone().lerp(C_SECONDARY, t);
            colArr[idx * 3]     = c.r;
            colArr[idx * 3 + 1] = c.g;
            colArr[idx * 3 + 2] = c.b;
        }
    }

    const gridGeo = new THREE.BufferGeometry();
    gridGeo.setAttribute('position', new THREE.BufferAttribute(posArr, 3));
    gridGeo.setAttribute('color',    new THREE.BufferAttribute(colArr, 3));

    const gridMat = new THREE.PointsMaterial({
        size: 0.08,
        vertexColors: true,
        transparent: true,
        opacity: 0.55,
        sizeAttenuation: true,
        depthWrite: false,
    });

    const grid = new THREE.Points(gridGeo, gridMat);
    grid.rotation.x = -0.35;
    scene.add(grid);

    /* ─────────────────────────────────────────────────────────
       LAYER 2 — Star / depth cloud
    ───────────────────────────────────────────────────────── */
    const STARS = 600;
    const starPos = new Float32Array(STARS * 3);
    const starCol = new Float32Array(STARS * 3);
    for (let i = 0; i < STARS; i++) {
        starPos[i * 3]     = (Math.random() - 0.5) * 80;
        starPos[i * 3 + 1] = (Math.random() - 0.5) * 60;
        starPos[i * 3 + 2] = (Math.random() - 0.5) * 60 - 10;
        const b = 0.3 + Math.random() * 0.7;
        starCol[i * 3]     = b;
        starCol[i * 3 + 1] = b * 0.85;
        starCol[i * 3 + 2] = b * 0.75;
    }

    const starGeo = new THREE.BufferGeometry();
    starGeo.setAttribute('position', new THREE.BufferAttribute(starPos, 3));
    starGeo.setAttribute('color',    new THREE.BufferAttribute(starCol, 3));

    const starMat = new THREE.PointsMaterial({
        size: 0.06,
        vertexColors: true,
        transparent: true,
        opacity: 0.35,
        sizeAttenuation: true,
        depthWrite: false,
    });

    const stars = new THREE.Points(starGeo, starMat);
    scene.add(stars);

    /* ─────────────────────────────────────────────────────────
       LAYER 3 — Wireframe icosahedra
    ───────────────────────────────────────────────────────── */
    const makeLine = (geo, color, opacity, x, y, z) => {
        const edges = new THREE.EdgesGeometry(geo);
        const mat   = new THREE.LineBasicMaterial({ color, transparent: true, opacity });
        const mesh  = new THREE.LineSegments(edges, mat);
        mesh.position.set(x, y, z);
        scene.add(mesh);
        return mesh;
    };

    const ico1 = makeLine(new THREE.IcosahedronGeometry(5, 1), '#ff4b1f', 0.12, 14, -4, -8);
    const ico2 = makeLine(new THREE.IcosahedronGeometry(3, 1), '#ff8c42', 0.08, -16, 5, -12);
    const knot = makeLine(new THREE.TorusKnotGeometry(2.5, 0.5, 80, 12), '#ff8c42', 0.06, -8, 8, -18);

    /* ── Mouse parallax ────────────────────────────────────── */
    const mouse = { x: 0, y: 0 };
    const onMouseMove = (e) => {
        mouse.x = (e.clientX / window.innerWidth  - 0.5) * 2;
        mouse.y = (e.clientY / window.innerHeight - 0.5) * 2;
    };
    window.addEventListener('mousemove', onMouseMove, { passive: true });

    /* ── Resize ────────────────────────────────────────────── */
    function resize() {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight || 600; // fallback if clientHeight is 0
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }

    const ro = new ResizeObserver(resize);
    ro.observe(canvas);
    resize();

    /* ── Animation loop ────────────────────────────────────── */
    let rafId = null;
    let active = true;

    function tick(t) {
        if (!active) return;
        rafId = requestAnimationFrame(tick);

        const s = t * 0.001;

        // Ripple the grid particles
        const pos = gridGeo.attributes.position;
        for (let i = 0; i < count; i++) {
            pos.setZ(i,
                Math.sin(s * 0.9 + phases[i]) * 0.7 +
                Math.cos(s * 0.5 + phases[i] * 0.7) * 0.4
            );
        }
        pos.needsUpdate = true;

        grid.rotation.z  = s * 0.018;
        stars.rotation.y = s * 0.004;
        stars.rotation.x = s * 0.002;

        ico1.rotation.x  = s * 0.12;
        ico1.rotation.y  = s * 0.09;
        ico2.rotation.x  = s * 0.08;
        ico2.rotation.z  = s * 0.11;
        knot.rotation.x  = s * 0.07;
        knot.rotation.y  = s * 0.05;

        // Camera parallax
        camera.position.x += (mouse.x * 2.5 - camera.position.x) * 0.04;
        camera.position.y += (-mouse.y * 1.5 - camera.position.y) * 0.04;
        camera.lookAt(scene.position);

        renderer.render(scene, camera);
    }

    rafId = requestAnimationFrame(tick);

    /* ── Cleanup ───────────────────────────────────────────── */
    return function cleanup() {
        active = false;
        if (rafId) cancelAnimationFrame(rafId);
        ro.disconnect();
        window.removeEventListener('mousemove', onMouseMove);
        renderer.dispose();
        [gridGeo, starGeo, gridMat, starMat].forEach(o => o.dispose());
    };
}
