export function initMagneticButtons() {
    const magneticBtns = document.querySelectorAll('.magnetic-btn');
    if (magneticBtns.length === 0) return;

    magneticBtns.forEach(btn => {
        let mouseX = 0;
        let mouseY = 0;
        let btnX = 0;
        let btnY = 0;
        let isHovered = false;

        let bound = btn.getBoundingClientRect();
        let centerX = bound.left + window.scrollX + bound.width / 2;
        let centerY = bound.top + window.scrollY + bound.height / 2;

        const handleMouseMove = (e) => {
            mouseX = e.clientX + window.scrollX;
            mouseY = e.clientY + window.scrollY;
            
            const dx = mouseX - centerX;
            const dy = mouseY - centerY;
            const dist = Math.sqrt(dx * dx + dy * dy);

            // Within 80px pull towards cursor
            if (dist < 80) {
                isHovered = true;
                const pull = 0.28;
                btnX += ((dx * pull) - btnX) * 0.15;
                btnY += ((dy * pull) - btnY) * 0.15;
            } else {
                isHovered = false;
                btnX += (0 - btnX) * 0.15;
                btnY += (0 - btnY) * 0.15;
            }
        };

        const handleScrollOrResize = () => {
            const rect = btn.getBoundingClientRect();
            centerX = rect.left + window.scrollX + rect.width / 2;
            centerY = rect.top + window.scrollY + rect.height / 2;
        };

        window.addEventListener('mousemove', handleMouseMove);
        window.addEventListener('resize', handleScrollOrResize);
        window.addEventListener('scroll', handleScrollOrResize, { passive: true });

        let animId;
        function update() {
            animId = requestAnimationFrame(update);
            btn.style.transform = `translate3d(${btnX}px, ${btnY}px, 0)`;
        }
        update();

        document.addEventListener('turbo:before-cache', () => {
            window.removeEventListener('mousemove', handleMouseMove);
            window.removeEventListener('resize', handleScrollOrResize);
            window.removeEventListener('scroll', handleScrollOrResize);
            cancelAnimationFrame(animId);
            btn.style.transform = '';
        }, { once: true });
    });
}
