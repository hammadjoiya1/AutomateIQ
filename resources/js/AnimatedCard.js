import { animate } from 'motion';

export function initAnimatedCard() {
    if (typeof window === 'undefined' || !window.Alpine) return;

    window.Alpine.data('animatedCard', (options = {}) => ({
        init() {
            // Read delay, y-offset, and duration from options
            const delay = parseFloat(options.delay || 0);
            const yOffset = parseFloat(options.y || 25);
            const duration = parseFloat(options.duration || 0.6);

            // Mount animation: fade-in and slide-up
            animate(this.$el, {
                opacity: [0, 1],
                y: [yOffset, 0]
            }, {
                duration: duration,
                easing: [0.16, 1, 0.3, 1], // easeOutExpo
                delay: delay
            });

            // Hover state animation (optional)
            if (options.hover !== false) {
                const hoverScale = parseFloat(options.scale || 1.02);
                const hoverY = parseFloat(options.hoverY || -4);

                this.$el.addEventListener('mouseenter', () => {
                    animate(this.$el, {
                        scale: hoverScale,
                        y: hoverY
                    }, {
                        duration: 0.3,
                        easing: [0.25, 1, 0.5, 1] // easeOutQuad
                    });
                });

                this.$el.addEventListener('mouseleave', () => {
                    animate(this.$el, {
                        scale: 1,
                        y: 0
                    }, {
                        duration: 0.3,
                        easing: [0.25, 1, 0.5, 1]
                    });
                });
            }
        }
    }));
}
