/**
 * gradient-blinds.js
 * Vanilla JS port of the React Bits GradientBlinds component.
 * Uses ogl (already in node_modules) for WebGL rendering.
 * Returns a cleanup function.
 */
import { Renderer, Program, Mesh, Triangle } from 'ogl';

const MAX_COLORS = 8;

function hexToRGB(hex) {
    const c = hex.replace('#', '').padEnd(6, '0');
    return [
        parseInt(c.slice(0, 2), 16) / 255,
        parseInt(c.slice(2, 4), 16) / 255,
        parseInt(c.slice(4, 6), 16) / 255,
    ];
}

function prepStops(stops) {
    const base = (stops && stops.length ? stops : ['#FF9FFC', '#5227FF']).slice(0, MAX_COLORS);
    if (base.length === 1) base.push(base[0]);
    while (base.length < MAX_COLORS) base.push(base[base.length - 1]);
    const arr = base.map(hexToRGB);
    const count = Math.max(2, Math.min(MAX_COLORS, stops?.length ?? 2));
    return { arr, count };
}

const VERTEX = `
attribute vec2 position;
attribute vec2 uv;
varying vec2 vUv;
void main() {
  vUv = uv;
  gl_Position = vec4(position, 0.0, 1.0);
}`;

const FRAGMENT = `
#ifdef GL_ES
precision mediump float;
#endif
uniform vec3  iResolution;
uniform vec2  iMouse;
uniform float iTime;
uniform float uAngle;
uniform float uNoise;
uniform float uBlindCount;
uniform float uSpotlightRadius;
uniform float uSpotlightSoftness;
uniform float uSpotlightOpacity;
uniform float uMirror;
uniform float uDistort;
uniform float uShineFlip;
uniform vec3  uColor0; uniform vec3 uColor1; uniform vec3 uColor2; uniform vec3 uColor3;
uniform vec3  uColor4; uniform vec3 uColor5; uniform vec3 uColor6; uniform vec3 uColor7;
uniform int   uColorCount;
varying vec2 vUv;

float rand(vec2 co){ return fract(sin(dot(co, vec2(12.9898,78.233))) * 43758.5453); }

vec2 rotate2D(vec2 p, float a){
  float c = cos(a); float s = sin(a);
  return mat2(c,-s,s,c)*p;
}

vec3 getGradientColor(float t){
  float tt = clamp(t,0.0,1.0);
  int count = uColorCount; if(count<2) count=2;
  float scaled = tt*float(count-1);
  float seg = floor(scaled); float f = fract(scaled);
  if(seg<1.0) return mix(uColor0,uColor1,f);
  if(seg<2.0&&count>2) return mix(uColor1,uColor2,f);
  if(seg<3.0&&count>3) return mix(uColor2,uColor3,f);
  if(seg<4.0&&count>4) return mix(uColor3,uColor4,f);
  if(seg<5.0&&count>5) return mix(uColor4,uColor5,f);
  if(seg<6.0&&count>6) return mix(uColor5,uColor6,f);
  if(seg<7.0&&count>7) return mix(uColor6,uColor7,f);
  if(count>7) return uColor7; if(count>6) return uColor6;
  if(count>5) return uColor5; if(count>4) return uColor4;
  if(count>3) return uColor3; if(count>2) return uColor2;
  return uColor1;
}

void mainImage(out vec4 fragColor, in vec2 fragCoord){
  vec2 uv0 = fragCoord.xy / iResolution.xy;
  float aspect = iResolution.x / iResolution.y;
  vec2 p = uv0*2.0-1.0; p.x *= aspect;
  vec2 pr = rotate2D(p,uAngle); pr.x /= aspect;
  vec2 uv = pr*0.5+0.5;
  vec2 uvMod = uv;
  if(uDistort>0.0){
    float a=uvMod.y*6.0; float b=uvMod.x*6.0; float w=0.01*uDistort;
    uvMod.x+=sin(a)*w; uvMod.y+=cos(b)*w;
  }
  float t = uvMod.x;
  if(uMirror>0.5) t=1.0-abs(1.0-2.0*fract(t));
  vec3 base = getGradientColor(t);
  vec2 offset = vec2(iMouse.x/iResolution.x, iMouse.y/iResolution.y);
  float d = length(uv0-offset);
  float r = max(uSpotlightRadius,1e-4);
  float dn = d/r;
  float spot = (1.0-2.0*pow(dn,uSpotlightSoftness))*uSpotlightOpacity;
  vec3 cir = vec3(spot);
  float stripe = fract(uvMod.x*max(uBlindCount,1.0));
  if(uShineFlip>0.5) stripe=1.0-stripe;
  vec3 ran = vec3(stripe);
  vec3 col = cir+base-ran;
  col += (rand(gl_FragCoord.xy+iTime)-0.5)*uNoise;
  fragColor = vec4(col,1.0);
}

void main(){
  vec4 color;
  mainImage(color, vUv*iResolution.xy);
  gl_FragColor = color;
}`;

/**
 * Mount a GradientBlinds WebGL effect into a container element.
 *
 * @param {HTMLElement} container
 * @param {Object} opts  — subset of the React component's props
 * @returns {Function}   cleanup()
 */
export function mountGradientBlinds(container, opts = {}) {
    const {
        gradientColors   = ['#a855f7', '#6d28d9'],
        angle            = 0,
        noise            = 0.25,
        blindCount       = 12,
        blindMinWidth    = 50,
        mouseDampening   = 0.15,
        mirrorGradient   = false,
        spotlightRadius  = 0.5,
        spotlightSoftness = 1,
        spotlightOpacity = 1,
        distortAmount    = 0,
        shineDirection   = 'left',
        dpr              = window.devicePixelRatio || 1,
    } = opts;

    const renderer = new Renderer({ dpr, alpha: true, antialias: true });
    const gl     = renderer.gl;
    const canvas = gl.canvas;

    canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;display:block;border-radius:inherit;';
    container.appendChild(canvas);

    const { arr: colorArr, count: colorCount } = prepStops(gradientColors);

    const uniforms = {
        iResolution:      { value: [gl.drawingBufferWidth, gl.drawingBufferHeight, 1] },
        iMouse:           { value: [0, 0] },
        iTime:            { value: 0 },
        uAngle:           { value: (angle * Math.PI) / 180 },
        uNoise:           { value: noise },
        uBlindCount:      { value: Math.max(1, blindCount) },
        uSpotlightRadius: { value: spotlightRadius },
        uSpotlightSoftness:{ value: spotlightSoftness },
        uSpotlightOpacity:{ value: spotlightOpacity },
        uMirror:          { value: mirrorGradient ? 1 : 0 },
        uDistort:         { value: distortAmount },
        uShineFlip:       { value: shineDirection === 'right' ? 1 : 0 },
        uColor0: { value: colorArr[0] }, uColor1: { value: colorArr[1] },
        uColor2: { value: colorArr[2] }, uColor3: { value: colorArr[3] },
        uColor4: { value: colorArr[4] }, uColor5: { value: colorArr[5] },
        uColor6: { value: colorArr[6] }, uColor7: { value: colorArr[7] },
        uColorCount: { value: colorCount },
    };

    const program  = new Program(gl, { vertex: VERTEX, fragment: FRAGMENT, uniforms });
    const geometry = new Triangle(gl);
    const mesh     = new Mesh(gl, { geometry, program });

    let firstResize = true;
    const mouseTarget = [0, 0];
    let lastT = 0;

    function resize() {
        const rect = container.getBoundingClientRect();
        renderer.setSize(rect.width, rect.height);
        uniforms.iResolution.value = [gl.drawingBufferWidth, gl.drawingBufferHeight, 1];

        const maxByMin = blindMinWidth > 0
            ? Math.max(1, Math.floor(rect.width / blindMinWidth))
            : Infinity;
        uniforms.uBlindCount.value = Math.max(1, Math.min(blindCount, maxByMin));

        if (firstResize) {
            firstResize = false;
            const cx = gl.drawingBufferWidth / 2;
            const cy = gl.drawingBufferHeight / 2;
            uniforms.iMouse.value = [cx, cy];
            mouseTarget[0] = cx; mouseTarget[1] = cy;
        }
    }

    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(container);

    function onPointerMove(e) {
        const rect = canvas.getBoundingClientRect();
        const scale = renderer.dpr || 1;
        mouseTarget[0] = (e.clientX - rect.left) * scale;
        mouseTarget[1] = (rect.height - (e.clientY - rect.top)) * scale;
        if (mouseDampening <= 0) uniforms.iMouse.value = [...mouseTarget];
    }
    canvas.addEventListener('pointermove', onPointerMove);
    // Also track global mouse for when cursor isn't directly over canvas
    window.addEventListener('mousemove', onPointerMove, { passive: true });

    let rafId = null;
    let running = true;

    function loop(t) {
        if (!running) return;
        rafId = requestAnimationFrame(loop);
        uniforms.iTime.value = t * 0.001;

        if (mouseDampening > 0) {
            if (!lastT) lastT = t;
            const dt  = (t - lastT) / 1000;
            lastT = t;
            const tau = Math.max(1e-4, mouseDampening);
            let factor = 1 - Math.exp(-dt / tau);
            if (factor > 1) factor = 1;
            const cur = uniforms.iMouse.value;
            cur[0] += (mouseTarget[0] - cur[0]) * factor;
            cur[1] += (mouseTarget[1] - cur[1]) * factor;
        } else {
            lastT = t;
        }

        try { 
            renderer.render({ scene: mesh }); 
        } catch (err) {
            console.error('[gradient-blinds-render]', err);
            throw err;
        }
    }

    rafId = requestAnimationFrame(loop);

    return function cleanup() {
        running = false;
        if (rafId) cancelAnimationFrame(rafId);
        ro.disconnect();
        canvas.removeEventListener('pointermove', onPointerMove);
        window.removeEventListener('mousemove', onPointerMove);
        if (canvas.parentElement === container) container.removeChild(canvas);
    };
}
