<section
    class="relative isolate flex min-h-screen w-full items-center justify-center overflow-hidden bg-background"
    role="region"
    aria-label="Glowing waves hero section"
    x-data="{
        init() {
            // Import the init function dynamically or rely on app.js to initialize it
            if (window.initGlowyWavesHero) {
                this.cleanup = window.initGlowyWavesHero('#glowy-canvas');
            }
        },
        destroy() {
            if (this.cleanup) this.cleanup();
        }
    }"
    @resize.window="if(cleanup) cleanup(); cleanup = window.initGlowyWavesHero('#glowy-canvas');"
>
    <!-- The Canvas for Waves -->
    <canvas
        id="glowy-canvas"
        class="absolute inset-0 h-full w-full"
        aria-hidden="true"
    ></canvas>

    <!-- Background glowing orbs -->
    <div class="absolute inset-0 -z-10 pointer-events-none">
        <div class="absolute left-1/2 top-0 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-foreground/[0.035] blur-[140px] dark:bg-foreground/[0.06]"></div>
        <div class="absolute bottom-0 right-0 h-[360px] w-[360px] rounded-full bg-foreground/[0.025] blur-[120px] dark:bg-foreground/[0.05]"></div>
        <div class="absolute top-1/2 left-1/4 h-[400px] w-[400px] rounded-full bg-primary/[0.02] blur-[150px] dark:bg-primary/[0.05]"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 mx-auto flex w-full max-w-6xl flex-col items-center px-6 py-24 text-center md:px-8 lg:px-12"
         x-data="{ shown: false }"
         x-init="setTimeout(() => shown = true, 100)">
        
        <div class="w-full transition-all duration-1000 transform"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
            
            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-border/40 bg-background/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-foreground/70 dark:border-border/60 dark:bg-background/70 dark:text-foreground/80 transition-all duration-700 delay-100 transform"
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-primary" aria-hidden="true">
                    <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
                </svg>
                Reactive canvas hero
            </div>

            <h1 class="mb-6 text-4xl font-semibold tracking-tight text-foreground md:text-6xl lg:text-7xl transition-all duration-700 delay-200 transform"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                Welcome to immersive <br class="hidden sm:block">
                <span class="bg-gradient-to-r from-primary via-primary/60 to-foreground/80 bg-clip-text text-transparent">
                    realtime playgrounds
                </span>
            </h1>

            <p class="mx-auto mb-10 max-w-3xl text-lg text-foreground/70 md:text-2xl transition-all duration-700 delay-300 transform"
               :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                Build living surfaces that respond to every interaction. Craft
                cinematic hero moments, responsive canvases, and luminous gradients
                without leaving your design system.
            </p>

            <div class="mb-10 flex flex-col items-center justify-center gap-4 sm:flex-row transition-all duration-700 delay-400 transform"
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                <a href="#" class="group inline-flex items-center justify-center gap-2 rounded-full bg-primary text-primary-foreground px-8 py-3 text-base font-medium uppercase tracking-[0.2em] transition-colors hover:bg-primary/90">
                    Launch Studio
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 transition-transform group-hover:translate-x-1" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="inline-flex items-center justify-center rounded-full border border-border/40 bg-background/60 px-8 py-3 text-base font-medium text-foreground/80 backdrop-blur transition-all hover:border-border/60 hover:bg-background/70 dark:border-border/50 dark:bg-background/40 dark:text-foreground/70 dark:hover:border-border/70 dark:hover:bg-background/50">
                    Explore stories
                </a>
            </div>

            <ul class="mb-12 flex flex-wrap items-center justify-center gap-3 text-xs uppercase tracking-[0.2em] text-foreground/70 dark:text-foreground/80 transition-all duration-700 delay-500 transform"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                <li class="rounded-full border border-border/40 bg-background/60 px-4 py-2 backdrop-blur dark:border-border/60 dark:bg-background/70">
                    Immersive visuals
                </li>
                <li class="rounded-full border border-border/40 bg-background/60 px-4 py-2 backdrop-blur dark:border-border/60 dark:bg-background/70">
                    Responsive motion
                </li>
                <li class="rounded-full border border-border/40 bg-background/60 px-4 py-2 backdrop-blur dark:border-border/60 dark:bg-background/70">
                    GPU friendly
                </li>
            </ul>

            <div class="grid gap-4 rounded-2xl border border-border/30 bg-background/60 p-6 backdrop-blur-sm dark:border-border/60 dark:bg-background/70 sm:grid-cols-3 transition-all duration-700 delay-700 transform"
                 :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
                
                <div class="space-y-1 transition-all duration-700 delay-[800ms] transform"
                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    <div class="text-xs uppercase tracking-[0.3em] text-foreground/50 dark:text-foreground/60">
                        Live installations
                    </div>
                    <div class="text-3xl font-semibold text-foreground">
                        320+
                    </div>
                </div>

                <div class="space-y-1 transition-all duration-700 delay-[900ms] transform"
                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    <div class="text-xs uppercase tracking-[0.3em] text-foreground/50 dark:text-foreground/60">
                        Latency
                    </div>
                    <div class="text-3xl font-semibold text-foreground">
                        8ms
                    </div>
                </div>

                <div class="space-y-1 transition-all duration-700 delay-[1000ms] transform"
                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    <div class="text-xs uppercase tracking-[0.3em] text-foreground/50 dark:text-foreground/60">
                        Teams onboarded
                    </div>
                    <div class="text-3xl font-semibold text-foreground">
                        120+
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
