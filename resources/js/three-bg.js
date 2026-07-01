/**
 * three-bg.js — Premium 3D WebGL Background (npm Three.js)
 */
import * as THREE from 'three';

export function initThreeBg(canvasId = 'three-bg-canvas') {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    const scene  = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 1000);
    camera.position.set(0, 0, 28);

    const C_A = new THREE.Color('#ff4b1f');
    const C_B = new THREE.Color('#ff8c42');

    // Rippling particle grid
    const G = 44, STP = 1.1, N = G * G;
    const pArr = new Float32Array(N * 3);
    const cArr = new Float32Array(N * 3);
    const ph   = new Float32Array(N);
    const half = (G - 1) * STP * 0.5;

    for (let i = 0; i < G; i++) {
        for (let j = 0; j < G; j++) {
            const idx = i * G + j;
            pArr[idx * 3]     = i * STP - half;
            pArr[idx * 3 + 1] = j * STP - half;
            pArr[idx * 3 + 2] = 0;
            ph[idx] = (i + j) * 0.22;
            const t = (i + j) / (G * 2);
            const c = C_A.clone().lerp(C_B, t);
            cArr[idx * 3]     = c.r;
            cArr[idx * 3 + 1] = c.g;
            cArr[idx * 3 + 2] = c.b;
        }
    }

    const gridGeo = new THREE.BufferGeometry();
    gridGeo.setAttribute('position', new THREE.BufferAttribute(pArr, 3));
    gridGeo.setAttribute('color',    new THREE.BufferAttribute(cArr, 3));
    const gridMat = new THREE.PointsMaterial({ size: 0.09, vertexColors: true, transparent: true, opacity: 0.65, sizeAttenuation: true, depthWrite: false });
    const grid = new THREE.Points(gridGeo, gridMat);
    grid.rotation.x = -0.35;
    scene.add(grid);

    // Star field
    const STARS = 600;
    const sP = new Float32Array(STARS * 3);
    const sC = new Float32Array(STARS * 3);
    for (let i = 0; i < STARS; i++) {
        sP[i*3]   = (Math.random()-0.5)*80;
        sP[i*3+1] = (Math.random()-0.5)*60;
        sP[i*3+2] = (Math.random()-0.5)*60-10;
        const b = 0.4 + Math.random()*0.6;
        sC[i*3]=b; sC[i*3+1]=b*0.85; sC[i*3+2]=b*0.75;
    }
    const starGeo = new THREE.BufferGeometry();
    starGeo.setAttribute('position', new THREE.BufferAttribute(sP, 3));
    starGeo.setAttribute('color',    new THREE.BufferAttribute(sC, 3));
    const starMat = new THREE.PointsMaterial({ size: 0.07, vertexColors: true, transparent: true, opacity: 0.45, sizeAttenuation: true, depthWrite: false });
    const stars = new THREE.Points(starGeo, starMat);
    scene.add(stars);

    // Wireframe accents
    const addWire = (geo, color, opacity, x, y, z) => {
        const obj = new THREE.LineSegments(new THREE.EdgesGeometry(geo), new THREE.LineBasicMaterial({ color, transparent: true, opacity }));
        obj.position.set(x, y, z);
        scene.add(obj);
        return obj;
    };
    const ico1 = addWire(new THREE.IcosahedronGeometry(5, 1), '#ff4b1f', 0.18,  14, -4,  -8);
    const ico2 = addWire(new THREE.IcosahedronGeometry(3, 1), '#ff8c42', 0.12, -16,  5, -12);
    const knot = addWire(new THREE.TorusKnotGeometry(2.5, 0.5, 80, 12), '#ff8c42', 0.08, -8, 8, -18);

    const mouse = { x: 0, y: 0 };
    const onMove = (e) => { mouse.x = (e.clientX/window.innerWidth-0.5)*2; mouse.y = (e.clientY/window.innerHeight-0.5)*2; };
    window.addEventListener('mousemove', onMove, { passive: true });

    function resize() {
        const section = canvas.parentElement;
        const w = Math.max(section ? section.clientWidth  : window.innerWidth,  1);
        const h = Math.max(section ? section.clientHeight : window.innerHeight, 600);
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
    const ro = new ResizeObserver(resize);
    ro.observe(canvas.parentElement ?? document.body);
    resize();

    let rafId = null, running = true;

    function tick(t) {
        if (!running) return;
        rafId = requestAnimationFrame(tick);
        const s = t * 0.001;
        const pos = gridGeo.attributes.position;
        for (let i = 0; i < N; i++) pos.setZ(i, Math.sin(s*0.9+ph[i])*0.7 + Math.cos(s*0.5+ph[i]*0.7)*0.4);
        pos.needsUpdate = true;
        grid.rotation.z  = s*0.018;
        stars.rotation.y = s*0.004; stars.rotation.x = s*0.002;
        ico1.rotation.x  = s*0.12;  ico1.rotation.y  = s*0.09;
        ico2.rotation.x  = s*0.08;  ico2.rotation.z  = s*0.11;
        knot.rotation.x  = s*0.07;  knot.rotation.y  = s*0.05;
        camera.position.x += (mouse.x*2.5 - camera.position.x)*0.04;
        camera.position.y += (-mouse.y*1.5 - camera.position.y)*0.04;
        camera.lookAt(scene.position);
        renderer.render(scene, camera);
    }
    rafId = requestAnimationFrame(tick);

    return function cleanup() {
        running = false;
        if (rafId) cancelAnimationFrame(rafId);
        ro.disconnect();
        window.removeEventListener('mousemove', onMove);
        renderer.dispose();
        gridGeo.dispose(); gridMat.dispose();
        starGeo.dispose(); starMat.dispose();
    };
}
