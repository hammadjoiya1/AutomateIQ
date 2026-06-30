# AutomateIQ — Design System & Motion Spec
### "Broadcast Control Room" — handoff doc for implementation

This is a build spec, not a mood board. Every value below is meant to be dropped directly into Tailwind config and `motion` calls. Stack assumption: Blade + Tailwind + Alpine.js + `motion` (npm) + `ogl` + Turbo, per the existing `package.json`.

---

## 0. Before any visual work — fix the foundation

1. **Resolve the Tailwind version conflict.** `package.json` has both `tailwindcss@^3.1.0` (devDependency) and `@tailwindcss/vite@^4.0.0`. Pick one architecture:
   - If staying v3: remove `@tailwindcss/vite`, keep `tailwind.config.js` as source of truth.
   - If moving to v4: remove `tailwindcss` v3 + its config-file pattern, move tokens into CSS `@theme` block instead. **Recommended**, since v4's CSS-first config pairs well with the token system below and is the current standard.
2. **Move `playwright` to `devDependencies`.** It has no business in the client bundle.
3. **Cut theme count from 4 → 2.** Keep "Control Room" (dark, primary, described below) and one light fallback. Retire Neon Cyber and Luxury Gold — both are absorbed into Control Room's identity.

---

## 1. Design tokens

### Color

```css
/* Control Room (dark, primary theme) */
--color-bg: #0B0D0F;        /* page background */
--color-surface: #15181C;   /* cards, panels */
--color-surface-raised: #1C2024; /* modals, dropdowns */
--color-border: #262B30;

--color-text: #F2F0EA;      /* warm off-white, not pure white */
--color-text-muted: #7A8088;

--color-accent: #FF4B1F;    /* tally-light red — "live" / active / primary CTA only */
--color-accent-dim: #7A2410; /* accent at rest / borders */
--color-signal: #00D9A3;    /* success, completed, positive metrics */
--color-signal-dim: #0A4536;

--color-warn: #FFB020;      /* caution states only, not decorative */
```

**Rule:** `--color-accent` (red) is reserved for the single most important action on a screen and for genuinely "live/recording" states (an active workflow run). If everything is red, nothing reads as live. `--color-signal` (green) is for completed/success. Don't let these bleed into decoration.

### Type

```css
--font-display: "Archivo Expanded", sans-serif;  /* headlines only */
--font-body: "Inter", sans-serif;                /* paragraphs, UI labels */
--font-mono: "JetBrains Mono", monospace;         /* ALL numeric data */
```

**Rule:** any number that represents live or counted data (credits, run counts, percentages, timestamps, durations) renders in `--font-mono`. This is non-negotiable — it's the detail that makes the control-room metaphor read as intentional rather than decorative.

### Spacing & radius

```css
--radius-sm: 6px;   /* buttons, badges, inputs */
--radius-md: 10px;  /* cards */
--radius-lg: 16px;  /* modals, hero panels */
/* Deliberately NOT fully-rounded (9999px) anywhere — equipment has edges, not pills */
```

---

## 2. Motion system

Install pattern (already have `motion` in package.json):

```js
import { animate, scroll, inView, spring } from "motion";
```

### Spring presets — define once, reuse everywhere

```js
// resources/js/motion-presets.js
export const springs = {
  micro:   { type: "spring", stiffness: 500, damping: 30 }, // button press, toggle, checkbox
  card:    { type: "spring", stiffness: 260, damping: 24 }, // hover lift, modal open, dropdown
  ambient: { type: "spring", stiffness: 80,  damping: 20 }, // waveform idle, OGL background drift
};

export const pageTransition = { duration: 0.15, easing: "ease-out" }; // Turbo nav — fast, no bounce
```

**Why page transitions don't spring:** navigation needs to feel instant and get out of the way. Springs are for things the user is *touching* (buttons, cards), not for things happening *to* them (page changes). Reserve the bounce for direct interaction.

### Core interaction patterns

**Button press (micro spring):**
```js
button.addEventListener("pointerdown", () => {
  animate(button, { scale: 0.96 }, springs.micro);
});
button.addEventListener("pointerup", () => {
  animate(button, { scale: 1 }, springs.micro);
});
```

**Card hover lift:**
```js
card.addEventListener("pointerenter", () => {
  animate(card, { y: -4, boxShadow: "0 12px 24px rgba(0,0,0,0.4)" }, springs.card);
});
card.addEventListener("pointerleave", () => {
  animate(card, { y: 0, boxShadow: "0 0px 0px rgba(0,0,0,0)" }, springs.card);
});
```

**Scroll-triggered reveal (sections, tool cards):**
```js
inView(".reveal", ({ target }) => {
  animate(target, { opacity: [0, 1], y: [24, 0] }, { ...springs.card, delay: 0.05 });
});
```

**Stat counter (dashboard "602 Credits", "18 Active Workflows"):**
```js
function animateCount(el, to, duration = 0.8) {
  const from = 0;
  animate((progress) => {
    el.textContent = Math.round(from + (to - from) * progress).toLocaleString();
  }, { duration, easing: "ease-out" });
}
```

**The signature waveform element** (idle ambient state, spikes during active generation):
```js
// Idle: slow breathing motion across bars
bars.forEach((bar, i) => {
  animate(bar, { scaleY: [0.3, 0.6, 0.3] },
    { ...springs.ambient, repeat: Infinity, delay: i * 0.08 });
});

// Active (e.g. tool is generating): faster, taller, accent color
function setActive(bars) {
  bars.forEach((bar, i) => {
    animate(bar, { scaleY: [0.4, 1, 0.5, 0.9, 0.3] },
      { duration: 0.6, repeat: Infinity, delay: i * 0.04 });
    bar.style.background = "var(--color-accent)";
  });
}
```
Use this exact component as: section dividers (idle), loading state inside tool cards when a generation is running, and a small persistent strip in the dashboard header showing "system is live."

### OGL background (hero only — use sparingly)

Keep it subtle: a slow-drifting noise/gradient mesh behind the hero, not a flashy particle show. Low opacity (8–15%), slow speed, dark base colors from the token set. One canvas, hero section only — don't put WebGL behind every section or it'll tank performance and read as excessive.

---

## 3. Component behavior spec

| Component | Default | Hover/Active | Notes |
|---|---|---|---|
| **Primary button** | `bg-accent`, mono-weight label | scale 0.96 on press (micro spring) | One per screen. Reserve red. |
| **Secondary button** | `border-border`, transparent bg | border brightens to `text-muted` | |
| **Tool card** | `bg-surface`, flat | lift -4px + shadow (card spring) | Icon gets a 4° rotate on hover, micro spring |
| **Nav links** | `text-muted` | `text-text`, underline draws in from left (150ms) | No bounce — nav is utility, not a toy |
| **Stat card (dashboard)** | static number | numbers count up on mount/scroll-into-view | Mono font, always |
| **Workflow node** | `bg-surface`, connecting line dim | line "completes" (stroke-dashoffset animation) on scroll | This is your signature layout moment |
| **Toast/notification** | slides in from top-right | spring-card on enter, ease-out fade on exit | Success = signal green left border; error = accent red |
| **Modal** | `surface-raised`, scale 0.95→1 + fade | backdrop fades independently (200ms linear) | card spring on the panel only |
| **Input focus** | `border-border` | `border-accent-dim` (NOT full accent — too loud) | |

---

## 4. Page-by-page implementation order

1. **Component library first.** Build button, card, nav, input, toast, modal as isolated Blade components/partials with the motion patterns above wired in. Get these exactly right before touching real pages — every page reuses them.
2. **Landing page hero.** OGL background, headline in Archivo Expanded, mono stat strip (+4x / -70% / <5min), primary CTA.
3. **Workflow section.** This is where the signature "signal chain" layout goes — nodes connected by self-drawing lines on scroll, replacing the current static numbered steps.
4. **Tool grid.** Cards with hover lift, icon micro-interaction.
5. **Dashboard.** Stat cards with count-up, the waveform strip as a live system indicator, tool history table with mono timestamps.
6. **Pricing, testimonials, footer.** Lowest motion priority — mostly static, maybe one scroll-reveal pass.

---

## 5. Guardrails (read before generating anything)

- **One bold element per screen.** The signature waveform/signal-chain motif is the memorable thing. Everything else stays quiet and disciplined around it.
- **Respect `prefers-reduced-motion`.** Wrap ambient/spring animations in a media query check; fall back to instant state changes.
- **Don't animate page navigation with springs** — keep Turbo transitions fast and linear.
- **Numbers are always mono.** This is the detail most likely to get skipped and most responsible for the "control room" feeling actually landing.
- **Red accent is rationed.** If a screen has more than one red element, something's wrong.
