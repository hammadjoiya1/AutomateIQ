/**
 * three-bg.js — Premium 3D WebGL Background
 *
 * Renders a multi-layer 3D scene:
 *   1. Floating particle field that ripples like a living surface
 *   2. Depth-fog star layer for cinematic parallax
 *   3. Wireframe icosahedron that slowly orbits the centre
 *
 * Uses Three.js (expected on window.THREE via CDN).
 * Returns a cleanup function for Turbo navigation safety.
 */
export function initThreeBg(canvasId = 'three-bg-canvas') {
    const THREE = window.THREE;
    if (!THREE) return null;

    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;

    /* ── Renderer ─────────────────────────────────────────────── */
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    const scene = new THREE.Scene();

    /* ── Camera ───────────────────────────────────────────────── */
    const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 1000);
    camera.position.set(0, 0, 28);

    /* ── Colour palette (matches CSS --color-primary ~#ff4b1f) ── */
    const PALETTE = {
        particlePrimary: new THREE.Color('#ff4b1f'),
        particleSecondary: new THREE.Color('#ff8c42'),
        particleMuted: new THREE.Color('#ffffff'),
        wireframe: new THREE.Color('#ff4b1f'),
    };

    /* ─────────────────────────────────────────────────────────────
       LAYER 1 — Rippling particle grid
       ───────────────────────────────────────────────────────────── */
    const GRID_SIZE = 44;       // points per axis
    const GRID_SPACING = 1.1;
    const particleCount = GRID_SIZE * GRID_SIZE;
    const gridGeo = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors    = new Float32Array(particleCount * 3);
    const offsets   = new Float32Array(particleCount);   // phase offsets

    const half = (GRID_SIZE - 1) * GRID_SPACING * 0.5;
    for (let i = 0; i < GRID_SIZE; i++) {
        for (let j = 0; j < GRID_SIZE; j++) {
            const idx = i * GRID_SIZE + j;
            positions[idx * 3]     = i * GRID_SPACING - half;
            positions[idx * 3 + 1] = j * GRID_SPACING - half;
            positions[idx * 3 + 2] = 0;
            offsets[idx] = (i + j) * 0.22;

            // Blend between primary and secondary colour based on diagonal
            const t = (i + j) / (GRID_SIZE * 2);
            const c = PALETTE.particlePrimary.clone().lerp(PALETTE.particleSecondary, t);
            colors[idx * 3]     = c.r;
            colors[idx * 3 + 1] = c.g;
            colors[idx * 3 + 2] = c.b;
        }
    }
    gridGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    gridGeo.setAttribute('color',    new THREE.BufferAttribute(colors, 3));

    const gridMat = new THREE.PointsMaterial({
        size: 0.08,
        vertexColors: true,
        transparent: true,
        opacity: 0.55,
        sizeAttenuation: true,
        depthWrite: false,
    });

    const gridPoints = new THREE.Points(gridGeo, gridMat);
    gridPoints.rotation.x = -0.35;
    scene.add(gridPoints);

    /* ─────────────────────────────────────────────────────────────
       LAYER 2 — Star / depth particle cloud
       ───────────────────────────────────────────────────────────── */
    const STAR_COUNT = 600;
    const starGeo = new THREE.BufferGeometry();
    const starPos = new Float32Array(STAR_COUNT * 3);
    const starCol = new Float32Array(STAR_COUNT * 3);
    for (let i = 0; i < STAR_COUNT; i++) {
        starPos[i * 3]     = (Math.random() - 0.5) * 80;
        starPos[i * 3 + 1] = (Math.random() - 0.5) * 60;
        starPos[i * 3 + 2] = (Math.random() - 0.5) * 60 - 10;
        const brightness = 0.3 + Math.random() * 0.7;
        starCol[i * 3]     = brightness;
        starCol[i * 3 + 1] = brightness * 0.85;
        starCol[i * 3 + 2] = brightness * 0.75;
    }
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

    /* ─────────────────────────────────────────────────────────────
       LAYER 3 — Wireframe icosahedron
       ───────────────────────────────────────────────────────────── */
    const icoGeo  = new THREE.IcosahedronGeometry(5, 1);
    const icoMat  = new THREE.MeshBasicMaterial({
        color: PALETTE.wireframe,
        wireframe: true,
        transparent: true,
        opacity: 0.07,
    });
    const ico = new THREE.Mesh(icoGeo, icoMat);
    ico.position.set(14, -4, -8);
    scene.add(ico);

    // Second smaller icosahedron on opposite side
    const ico2Geo = new THREE.IcosahedronGeometry(3, 1);
    const ico2Mat = new THREE.MeshBasicMaterial({
        color: PALETTE.wireframe,
        wireframe: true,
        transparent: true,
        opacity: 0.05,
    });
    const ico2 = new THREE.Mesh(ico2Geo, ico2Mat);
    ico2.position.set(-16, 5, -12);
    scene.add(ico2);

    /* ─────────────────────────────────────────────────────────────
       LAYER 4 — Torus knot accent (very subtle)
       ───────────────────────────────────────────────────────────── */
    const knotGeo = new THREE.TorusKnotGeometry(2.5, 0.5, 80, 12);
    const knotMat = new THREE.MeshBasicMaterial({
        color: '#ff8c42',
        wireframe: true,
        transparent: true,
        opacity: 0.04,
    });
    const knot = new THREE.Mesh(knotGeo, knotMat);
    knot.position.set(-8, 8, -18);
    scene.add(knot);

    /* ── Mouse parallax tracking ──────────────────────────────── */
    const mouse = { x: 0, y: 0 };
    const onMouseMove = (e) => {
        mouse.x = (e.clientX / window.innerWidth  - 0.5) * 2;
        mouse.y = (e.clientY / window.innerHeight - 0.5) * 2;
    };
    window.addEventListener('mousemove', onMouseMove, { passive: true });

    /* ── Resize handler ───────────────────────────────────────── */
    function resize() {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(canvas);
    resize();

    /* ── Animation loop ───────────────────────────────────────── */
    let animId = null;
    let running = true;

    function animate(t) {
        if (!running) return;
        animId = requestAnimationFrame(animate);

        const time = t * 0.001;

        /* --- Ripple grid --- */
        const pos = gridGeo.attributes.position;
        for (let i = 0; i < particleCount; i++) {
            const phase = offsets[i];
            pos.setZ(i, Math.sin(time * 0.9 + phase) * 0.7 + Math.cos(time * 0.5 + phase * 0.7) * 0.4);
        }
        pos.needsUpdate = true;

        /* --- Grid slow drift --- */
        gridPoints.rotation.z = time * 0.018;

        /* --- Stars slow drift --- */
        stars.rotation.y = time * 0.004;
        stars.rotation.x = time * 0.002;

        /* --- Icosahedrons --- */
        ico.rotation.x  = time * 0.12;
        ico.rotation.y  = time * 0.09;
        ico2.rotation.x = time * 0.08;
        ico2.rotation.z = time * 0.11;

        /* --- Torus knot --- */
        knot.rotation.x = time * 0.07;
        knot.rotation.y = time * 0.05;

        /* --- Camera parallax (smooth follow) --- */
        camera.position.x += (mouse.x * 2.5 - camera.position.x) * 0.04;
        camera.position.y += (-mouse.y * 1.5 - camera.position.y) * 0.04;
        camera.lookAt(scene.position);

        renderer.render(scene, camera);
    }

    animId = requestAnimationFrame(animate);

    /* ── Cleanup ──────────────────────────────────────────────── */
    return function cleanup() {
        running = false;
        if (animId) cancelAnimationFrame(animId);
        resizeObserver.disconnect();
        window.removeEventListener('mousemove', onMouseMove);
        renderer.dispose();
        gridGeo.dispose();
        gridMat.dispose();
        starGeo.dispose();
        starMat.dispose();
        icoGeo.dispose();
        icoMat.dispose();
        ico2Geo.dispose();
        ico2Mat.dispose();
        knotGeo.dispose();
        knotMat.dispose();
    };
}
