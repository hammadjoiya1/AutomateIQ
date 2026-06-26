function u(){document.querySelectorAll("[data-tilt], .card-3d, .tilt-3d").forEach(t=>{t.style.transformStyle="preserve-3d",t.style.transition="transform 0.1s ease-out",t.addEventListener("mousemove",s=>{const o=t.getBoundingClientRect(),e=s.clientX-o.left,n=s.clientY-o.top,a=o.width/2,i=o.height/2,l=(n-i)/45,d=(a-e)/45;t.style.transform=`
                perspective(1000px) 
                rotateX(${l}deg) 
                rotateY(${d}deg) 
                scale3d(1.01, 1.01, 1.01)
            `;const c=e/o.width*100,m=n/o.height*100;t.style.background=`
                radial-gradient(
                    circle at ${c}% ${m}%, 
                    rgba(91, 33, 182, 0.15), 
                    transparent 50%
                ),
                ${getComputedStyle(t).backgroundColor||"white"}
            `}),t.addEventListener("mouseleave",()=>{t.style.transform="perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)",t.style.background=""})})}function f(){const r=document.querySelectorAll("[data-parallax], .parallax");window.addEventListener("scroll",()=>{const t=window.pageYOffset;r.forEach(s=>{const o=parseFloat(s.dataset.speed)||.5,n=(s.dataset.direction||"up")==="up"?-(t*o):t*o;s.style.transform=`translate3d(0, ${n}px, 0)`})},{passive:!0})}function p(){if(document.querySelectorAll("[data-float], .float-3d").forEach((t,s)=>{const o=s*.5,e=4+Math.random()*2;t.style.animation=`
            float3D ${e}s ease-in-out ${o}s infinite,
            rotate3D ${e*2}s linear ${o}s infinite
        `}),!document.getElementById("float3d-keyframes")){const t=document.createElement("style");t.id="float3d-keyframes",t.textContent=`
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
        `,document.head.appendChild(t)}}function v(){document.querySelectorAll("[data-magnetic], .btn-magnetic, .magnetic").forEach(t=>{t.addEventListener("mousemove",s=>{const o=t.getBoundingClientRect(),e=s.clientX-o.left-o.width/2,n=s.clientY-o.top-o.height/2,a=parseFloat(t.dataset.magneticStrength)||.3;t.style.transform=`translate(${e*a}px, ${n*a}px)`}),t.addEventListener("mouseleave",()=>{t.style.transform="translate(0, 0)",t.style.transition="transform 0.3s ease-out"}),t.addEventListener("mouseenter",()=>{t.style.transition="transform 0.1s ease-out"})})}function h(){const r=document.querySelectorAll("[data-scroll-animate], .scroll-animate"),t=new IntersectionObserver(s=>{s.forEach(o=>{if(o.isIntersecting){const e=o.target,n=e.dataset.animation||"fadeInUp",a=e.dataset.delay||"0";e.style.animationDelay=`${a}ms`,e.classList.add(`animate-${n}`),e.classList.add("animated"),t.unobserve(e)}})},{threshold:.1,rootMargin:"0px 0px -50px 0px"});r.forEach(s=>{s.style.opacity="0",t.observe(s)})}function E(){if(window.innerWidth<768)return;const r=document.createElement("div");r.className="mouse-trail-container",r.style.cssText=`
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9999;
        overflow: hidden;
    `,document.body.appendChild(r);const t=[],s=15;for(let a=0;a<s;a++){const i=document.createElement("div");i.style.cssText=`
            position: absolute;
            width: ${8-a*.4}px;
            height: ${8-a*.4}px;
            background: linear-gradient(135deg, rgba(91, 33, 182, ${.6-a*.04}), rgba(236, 72, 153, ${.4-a*.02}));
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
        `,r.appendChild(i),t.push({el:i,x:0,y:0})}let o=0,e=0;document.addEventListener("mousemove",a=>{o=a.clientX,e=a.clientY});function n(){let a=o,i=e;t.forEach((l,d)=>{const c=a+(l.x-a)*.3,m=i+(l.y-i)*.3;l.x=c,l.y=m,l.el.style.left=`${c}px`,l.el.style.top=`${m}px`,a=c,i=m}),requestAnimationFrame(n)}n()}function y(){const r=document.createElement("div");r.className="scroll-progress-bar",r.style.cssText=`
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #5B21B6, #EC4899, #7C3AED);
        z-index: 9999;
        transition: width 0.1s ease-out;
        box-shadow: 0 0 10px rgba(91, 33, 182, 0.5);
    `,document.body.appendChild(r),window.addEventListener("scroll",()=>{const t=window.pageYOffset,s=document.documentElement.scrollHeight-window.innerHeight,o=t/s*100;r.style.width=`${o}%`},{passive:!0})}function g(){const r=document.querySelectorAll("[data-stagger-reveal]"),t=new IntersectionObserver(s=>{s.forEach(o=>{if(o.isIntersecting){const e=o.target.children;Array.from(e).forEach(n=>{n.style.opacity="0",n.style.transform="translateY(10px)",n.style.transition="all 0.2s cubic-bezier(0.16, 1, 0.3, 1) 0ms",n.style.opacity="1",n.style.transform="translateY(0)"}),t.unobserve(o.target)}})},{threshold:.1});r.forEach(s=>t.observe(s))}function x(){const r=document.querySelectorAll("[data-scramble]"),t=["Innovation","Creativity","Automation","Revolution","Viral Content","AI Powered","Game Changer","Next Level","Pro Creator","Go Viral","Scale Fast","Smart Tools"];class s{constructor(n){this.el=n,this.originalText=n.textContent,this.hasBlur=n.hasAttribute("data-scramble-blur")}scramble(){let n=0;const a=8;this.hasBlur&&(this.el.style.filter="blur(6px)",this.el.style.transition="filter 0.2s ease-out");const i=setInterval(()=>{if(this.el.textContent=t[Math.floor(Math.random()*t.length)],n++,this.hasBlur){const l=n/a,d=Math.max(0,6-l*8);this.el.style.filter=`blur(${d}px)`}n>=a&&(clearInterval(i),this.el.textContent=this.originalText,this.el.style.filter="blur(0)")},100)}}const o=new IntersectionObserver(e=>{e.forEach(n=>{if(n.isIntersecting){const a=new s(n.target);setTimeout(()=>a.scramble(),300),o.unobserve(n.target)}})});r.forEach(e=>o.observe(e))}function w(){const r=document.querySelector(".hero, [data-hero-3d]");if(!r)return;const t=document.createElement("div");t.className="hero-3d-scene",t.style.cssText=`
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    `,r.style.position="relative",r.insertBefore(t,r.firstChild),[{type:"sphere",size:80,x:"10%",y:"20%",delay:0},{type:"cube",size:60,x:"85%",y:"30%",delay:.5},{type:"torus",size:100,x:"75%",y:"70%",delay:1},{type:"sphere",size:40,x:"20%",y:"75%",delay:1.5},{type:"cube",size:50,x:"50%",y:"15%",delay:2}].forEach(e=>{const n=document.createElement("div");n.className=`hero-shape shape-${e.type}`,n.style.cssText=`
            position: absolute;
            left: ${e.x};
            top: ${e.y};
            width: ${e.size}px;
            height: ${e.size}px;
            background: linear-gradient(135deg, rgba(91, 33, 182, 0.2), rgba(236, 72, 153, 0.15));
            border: 1px solid rgba(91, 33, 182, 0.2);
            border-radius: ${e.type==="sphere"||e.type==="torus"?"50%":"12px"};
            animation: heroFloat${Math.floor(Math.random()*3)} 8s ease-in-out ${e.delay}s infinite;
            backdrop-filter: blur(5px);
        `,t.appendChild(n)});const o=document.createElement("style");o.textContent=`
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
    `,document.head.appendChild(o),t.querySelectorAll(".hero-shape").forEach((e,n)=>{parseFloat(e.style.left),parseFloat(e.style.top)}),document.addEventListener("mousemove",e=>{const n=r.getBoundingClientRect();if(e.clientY>n.bottom+200)return;const a=(e.clientX/window.innerWidth-.5)*2,i=(e.clientY/window.innerHeight-.5)*2;t.querySelectorAll(".hero-shape").forEach((l,d)=>{const c=(d+1)*20;l.style.marginLeft=`${a*c}px`,l.style.marginTop=`${i*c}px`})})}function b(){document.querySelectorAll('a[href^="#"]').forEach(r=>{r.addEventListener("click",function(t){t.preventDefault();const s=document.querySelector(this.getAttribute("href"));s&&s.scrollIntoView({behavior:"smooth",block:"start"})})}),document.querySelectorAll("a, button").forEach(r=>{r.addEventListener("touchstart",()=>{},{passive:!0})})}function $(){u(),f(),p(),h(),y(),g(),x(),b(),console.log("✨ Premium 3D Effects initialized")}export{u as init3DTilt,$ as initAll3DEffects,p as initFloatingObjects,w as initHero3DScene,v as initMagneticElements,E as initMouseTrail,f as initParallax,h as initScrollAnimations,y as initScrollProgress,b as initSmoothSections,g as initStaggeredReveal,x as initTextScramble};
