/**
 * spotlight.js
 * Tracks mouse movements over cards to set local --mouse-x and --mouse-y CSS variables.
 * Used for premium mouse-tracking spotlight gradient backgrounds and border glows.
 */
export function initSpotlightCards() {
    const cards = document.querySelectorAll('.spotlight-card');
    
    function onMouseMove(e) {
        const card = e.currentTarget;
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);
    }

    cards.forEach((card) => {
        card.addEventListener('mousemove', onMouseMove, { passive: true });
    });

    return function cleanup() {
        cards.forEach((card) => {
            card.removeEventListener('mousemove', onMouseMove);
        });
    };
}
