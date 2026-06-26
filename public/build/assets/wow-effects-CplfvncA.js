function h(){document.querySelectorAll(".btn-primary, .btn").forEach(t=>{t.addEventListener("click",e=>{x(e.clientX,e.clientY)})})}function x(t,e){const i=["#5B21B6","#7C3AED","#EC4899","#A855F7","#8B5CF6"];for(let o=0;o<25;o++){let f=function(){p+=d,a+=u+2,c-=.02,m-=.01,n.style.transform=`translate(${p}px, ${a}px) scale(${Math.max(0,m)})`,n.style.opacity=c,c>0?requestAnimationFrame(f):n.remove()};const n=document.createElement("div");n.style.cssText=`
            position: fixed;
            left: ${t}px;
            top: ${e}px;
            width: 10px;
            height: 10px;
            background: ${i[Math.floor(Math.random()*i.length)]};
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
        `,document.body.appendChild(n);const s=Math.PI*2/25*o,l=5+Math.random()*10,d=Math.cos(s)*l,u=Math.sin(s)*l;let p=0,a=0,c=1,m=1;requestAnimationFrame(f)}}function y(){if(document.querySelectorAll(".btn, button").forEach(t=>{t.style.position="relative",t.style.overflow="hidden",t.addEventListener("click",e=>{const i=t.getBoundingClientRect(),r=e.clientX-i.left,o=e.clientY-i.top,n=document.createElement("span");n.style.cssText=`
                position: absolute;
                left: ${r}px;
                top: ${o}px;
                width: 0;
                height: 0;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                transform: translate(-50%, -50%);
                animation: rippleExpand 0.6s ease-out forwards;
                pointer-events: none;
            `,t.appendChild(n),setTimeout(()=>n.remove(),600)})}),!document.getElementById("ripple-keyframe")){const t=document.createElement("style");t.id="ripple-keyframe",t.textContent=`
            @keyframes rippleExpand {
                to {
                    width: 400px;
                    height: 400px;
                    opacity: 0;
                }
            }
        `,document.head.appendChild(t)}}function g(){document.querySelectorAll("[data-typewriter]").forEach(e=>{const i=e.textContent;e.textContent="",e.style.borderRight="2px solid #5B21B6";let r=0;function o(){r<i.length?(e.textContent+=i.charAt(r),r++,setTimeout(o,50+Math.random()*50)):e.style.borderRight="none"}const n=new IntersectionObserver(s=>{s[0].isIntersecting&&(o(),n.disconnect())});n.observe(e)})}function v(){const t=document.querySelectorAll("[data-counter]"),e=new IntersectionObserver(i=>{i.forEach(r=>{if(r.isIntersecting){let d=function(u){const p=u-l,a=Math.min(p/s,1),c=1-Math.pow(1-a,3);o.textContent=Math.floor(c*n).toLocaleString(),a<1?requestAnimationFrame(d):o.textContent=n.toLocaleString()};const o=r.target,n=parseInt(o.dataset.counter),s=2e3,l=performance.now();requestAnimationFrame(d),e.unobserve(o)}})});t.forEach(i=>e.observe(i))}function E(){if(window.innerWidth<768)return;const t=document.createElement("div");t.style.cssText=`
        position: fixed;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(91, 33, 182, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 9998;
        transition: transform 0.1s ease-out;
        transform: translate(-50%, -50%);
    `,document.body.appendChild(t),document.addEventListener("mousemove",e=>{t.style.left=e.clientX+"px",t.style.top=e.clientY+"px"})}function b(){h(),y(),g(),v(),console.log("🎆 Wow Effects initialized!")}export{v as initCounterAnimation,h as initParticleBurst,y as initRippleEffect,E as initSpotlightCursor,g as initTypewriter,b as initWowEffects};
