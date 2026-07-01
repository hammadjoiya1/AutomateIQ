<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:py-10" x-data="{
        step: 1,
        niche: 'fitness',
        product: '',
        loading: false,
        simulatedOutput: '',
        hookGenerated: false,
        savedToLibrary: false,
        workflowEnabled: true,
        typing: false,
        toast: false,
        hooks: {
            fitness: '🏋️‍♂️ Hard truth: Your workouts are useless if you\'re not doing this one simple thing... Here is the 30-second daily fix.',
            saas: '💻 90% of solopreneurs waste 15 hours a week on manual tasks. Here is the AI workflow that automates your entire pipeline.',
            finance: '📈 The banks do not want you to know about this compound interest loophole. How to turn $5 a day into a massive retirement nest.'
        },
        generateHook() {
            this.loading = true;
            this.simulatedOutput = '';
            this.hookGenerated = false;
            
            setTimeout(() => {
                this.loading = false;
                let text = this.hooks[this.niche] || '🔥 Stop scrolling! This simple trick will double your results in less than 7 days.';
                if (this.product.trim() !== '') {
                    text = '🚀 Testing ' + this.product + '... ' + text;
                }
                this.typewriter(text);
            }, 1500);
        },
        typewriter(text) {
            let i = 0;
            this.typing = true;
            let timer = setInterval(() => {
                if (i < text.length) {
                    this.simulatedOutput += text.charAt(i);
                    i++;
                } else {
                    clearInterval(timer);
                    this.typing = false;
                    this.hookGenerated = true;
                }
            }, 15);
        },
        saveLibrary() {
            this.savedToLibrary = true;
            this.toast = true;
            setTimeout(() => { this.toast = false; }, 3000);
        }
    }">
        <!-- Steps Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between max-w-lg mx-auto">
                <template x-for="s in [1, 2, 3, 4, 5]">
                    <div class="flex items-center flex-1 last:flex-none">
                        <div 
                            :class="step >= s ? 'bg-primary border-primary text-white' : 'bg-background border-border text-text/40'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all duration-300 shadow-md text-sm"
                            x-text="s"
                        ></div>
                        <div 
                            x-show="s < 5"
                            :class="step > s ? 'bg-primary' : 'bg-surface/10'"
                            class="h-0.5 flex-1 mx-2 transition-all duration-300"
                        ></div>
                    </div>
                </template>
            </div>
            <div class="text-center text-xs text-text/60 mt-3 font-semibold uppercase tracking-wider">
                <span x-show="step === 1">Step 1: Welcome to AutomateIQ</span>
                <span x-show="step === 2">Step 2: Generate Your First Hook</span>
                <span x-show="step === 3">Step 3: Save to Library</span>
                <span x-show="step === 4">Step 4: Enable Automated Workflows</span>
                <span x-show="step === 5">Step 5: Unlock Your Factory</span>
            </div>
        </div>

        <!-- Onboarding Cards Container -->
        <div class="card p-8 border border-border bg-surface/50 backdrop-blur-md rounded-3xl shadow-2xl relative overflow-hidden min-h-[480px] flex flex-col justify-between">
            
            <!-- Glow Accents -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Toast Success Message -->
            <div x-show="toast" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 class="absolute top-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg flex items-center gap-2 z-50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Saved to Library Successfully!
            </div>

            <!-- Content Area -->
            <div class="flex-grow flex flex-col justify-center my-4">
                
                <!-- STEP 1: WELCOME -->
                <div x-show="step === 1" x-transition class="space-y-6 text-center max-w-xl mx-auto">
                    <div class="w-20 h-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto shadow-inner ring-8 ring-primary/5">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-text font-display">Get Your First Win in 5 Minutes</h2>
                    <p class="text-text/70 leading-relaxed text-sm">
                        Welcome to <strong>AutomateIQ</strong>, the ultimate faceless AI video automation pipeline. Let us guide you through our core workflow so you can launch your first automation immediately.
                    </p>
                    <div class="p-4 bg-background/40 border border-border rounded-2xl flex items-center gap-4 text-left">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-ping"></div>
                        <div class="text-xs text-text/60">
                            Your account is loaded with subscription credits to start running tools and generating videos immediately!
                        </div>
                    </div>
                </div>

                <!-- STEP 2: RUN AI TOOL MOCKUP -->
                <div x-show="step === 2" x-transition class="space-y-6 max-w-2xl mx-auto w-full">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-text font-display">1) Run Your First AI Tool</h2>
                        <p class="text-text/60 text-xs mt-1">Select a niche and input a focus keyword to generate a high-converting hook.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start mt-6">
                        <!-- Tool inputs mockup -->
                        <div class="space-y-4 bg-background/30 p-5 rounded-2xl border border-border">
                            <div>
                                <label class="block text-xs font-semibold text-text/70 mb-1.5">Target Niche</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button @click="niche = 'fitness'" :class="niche === 'fitness' ? 'btn-primary text-xs py-1.5' : 'bg-surface/50 border border-border text-text/60 text-xs py-1.5'" class="rounded-xl font-medium transition">Fitness</button>
                                    <button @click="niche = 'saas'" :class="niche === 'saas' ? 'btn-primary text-xs py-1.5' : 'bg-surface/50 border border-border text-text/60 text-xs py-1.5'" class="rounded-xl font-medium transition">Tech/SaaS</button>
                                    <button @click="niche = 'finance'" :class="niche === 'finance' ? 'btn-primary text-xs py-1.5' : 'bg-surface/50 border border-border text-text/60 text-xs py-1.5'" class="rounded-xl font-medium transition">Finance</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text/70 mb-1.5">Product or Topic (Optional)</label>
                                <input type="text" x-model="product" placeholder="e.g. Fat Loss Formula, Coding Course" class="w-full bg-surface border border-border rounded-xl px-3 py-2 text-xs text-text placeholder:text-text/30">
                            </div>
                            <button @click="generateHook()" :disabled="loading" class="btn btn-primary btn-sm w-full py-2.5 flex items-center justify-center gap-2">
                                <span x-show="!loading">Generate Hook</span>
                                <span x-show="loading" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...</span>
                            </button>
                        </div>

                        <!-- Generation Output Monitor -->
                        <div class="h-[210px] bg-background/50 border border-border rounded-2xl p-5 flex flex-col justify-between relative shadow-inner">
                            <div class="absolute top-3 left-4 text-[9px] uppercase tracking-wider font-semibold text-text/30">AI Live Terminal</div>
                            <div class="flex-grow flex items-center justify-center mt-3 text-xs leading-relaxed text-text/80 font-mono">
                                <span x-show="!loading && !simulatedOutput && !typing" class="text-text/40 italic">Your generated script hook will appear here...</span>
                                <span x-show="loading" class="text-primary font-bold animate-pulse">Running DeepAI Copilot Model...</span>
                                <span x-show="simulatedOutput" x-text="simulatedOutput"></span>
                                <span x-show="typing" class="w-1.5 h-4 bg-primary inline-block ml-0.5 animate-pulse"></span>
                            </div>
                            <div class="pt-3 border-t border-border flex justify-between items-center text-[10px] text-text/50">
                                <span>Status: <strong x-text="hookGenerated ? 'Generated' : (loading ? 'Running' : 'Idle')"></strong></span>
                                <span x-show="hookGenerated" class="text-emerald-500 font-semibold flex items-center gap-1">✓ Complete</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: SAVE TO LIBRARY -->
                <div x-show="step === 3" x-transition class="space-y-6 max-w-2xl mx-auto w-full">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-text font-display">2) Save Outputs to Your Library</h2>
                        <p class="text-text/60 text-xs mt-1">Organize and save your highest performing creations in collections to use later.</p>
                    </div>

                    <div class="bg-background/30 border border-border p-6 rounded-3xl max-w-lg mx-auto space-y-6">
                        <!-- Simulated Saved Hook snippet -->
                        <div class="p-4 rounded-xl bg-surface border border-border relative shadow-md">
                            <p class="text-xs font-mono text-text/80 leading-relaxed" x-text="simulatedOutput || '🏋️‍♂️ Hard truth: Your workouts are useless if you\'re not doing this one simple thing... Here is the 30-second daily fix.'"></p>
                            <div class="absolute -bottom-2.5 -right-2 bg-primary/20 border border-primary/30 text-primary text-[9px] px-2 py-0.5 rounded-full font-bold uppercase">Hook Asset</div>
                        </div>

                        <!-- Collection targets -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                            <div :class="savedToLibrary ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-border bg-background/50'" class="p-3 border rounded-2xl flex items-center gap-3 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-xs font-bold text-text">TikTok Vault</div>
                                    <div class="text-[10px] text-text/50" x-text="savedToLibrary ? '1 Item' : '0 Items'"></div>
                                </div>
                            </div>
                            <div class="p-3 border border-border bg-background/50 rounded-2xl flex items-center gap-3 opacity-60">
                                <div class="w-8 h-8 rounded-lg bg-accent/10 text-accent flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-xs font-bold text-text">YouTube Shorts</div>
                                    <div class="text-[10px] text-text/50">0 Items</div>
                                </div>
                            </div>
                        </div>

                        <button @click="saveLibrary()" :disabled="savedToLibrary" :class="savedToLibrary ? 'bg-emerald-500 text-white border-transparent cursor-not-allowed shadow-none' : 'btn-primary hover:shadow-primary/20'" class="btn w-full py-3 font-semibold text-sm flex items-center justify-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8"/></svg>
                            <span x-text="savedToLibrary ? 'Saved to Collection' : 'Save to Library Collection'"></span>
                        </button>
                    </div>
                </div>

                <!-- STEP 4: WORKFLOW SETUP -->
                <div x-show="step === 4" x-transition class="space-y-6 max-w-2xl mx-auto w-full">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-text font-display">3) Automate With Workflows</h2>
                        <p class="text-text/60 text-xs mt-1">Connect your library collections to scheduling triggers to post automatically.</p>
                    </div>

                    <div class="bg-background/30 border border-border p-6 rounded-3xl max-w-md mx-auto space-y-6">
                        <div class="flex items-center justify-between p-4 bg-surface rounded-2xl border border-border shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-accent/10 text-indigo-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-sm font-bold text-text">Simulated TikTok Poster</div>
                                    <div class="text-xs text-text/60">Every Monday @ 9:00 AM</div>
                                </div>
                            </div>
                            <!-- Switch -->
                            <button @click="workflowEnabled = !workflowEnabled" 
                                    :class="workflowEnabled ? 'bg-primary justify-end' : 'bg-surface-raised justify-start'" 
                                    class="w-12 h-6 rounded-full p-0.5 flex items-center transition-colors focus:outline-none">
                                <span class="bg-surface w-5 h-5 rounded-full shadow transition-transform duration-300"></span>
                            </button>
                        </div>

                        <!-- Flow indicators -->
                        <div class="flex flex-col items-center gap-2 py-4">
                            <div class="text-xs font-semibold text-text/60">Automated Pipeline Flow</div>
                            <div class="flex items-center justify-center gap-4 text-xs font-mono text-text/70 mt-2">
                                <span class="bg-background px-3 py-1.5 rounded-lg border border-border">Saved Hook</span>
                                <span class="text-primary font-bold">➔</span>
                                <span :class="workflowEnabled ? 'border-primary text-primary' : 'border-border opacity-50'" class="bg-background px-3 py-1.5 rounded-lg border transition">TikTok Auto-Schedule</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: FINISH -->
                <div x-show="step === 5" x-transition class="space-y-6 text-center max-w-xl mx-auto">
                    <div class="w-20 h-20 bg-emerald-500/10 text-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-inner ring-8 ring-emerald-500/5">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-text font-display">Onboarding Complete!</h2>
                    <p class="text-text/70 leading-relaxed text-sm">
                        You have successfully completed the walkthrough! You are ready to start generating real high-fidelity videos and building automated workflows.
                    </p>

                    <div class="p-6 bg-background/50 border border-border rounded-3xl max-w-md mx-auto grid grid-cols-2 gap-4 text-left">
                        <div class="space-y-1">
                            <span class="text-[10px] text-text/50 font-bold uppercase tracking-wider">Plan Access</span>
                            <div class="text-lg font-bold text-primary capitalize">{{ Auth::user()->plan }} Plan</div>
                        </div>
                        <div class="space-y-1 border-l border-border pl-4">
                            <span class="text-[10px] text-text/50 font-bold uppercase tracking-wider">Starting Balance</span>
                            <div class="text-lg font-bold text-text">{{ number_format(Auth::user()->credits) }} Credits</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Action Buttons -->
            <div class="pt-6 border-t border-border flex items-center justify-between">
                <button 
                    x-show="step > 1" 
                    @click="step--" 
                    class="btn-secondary px-5 py-2.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>
                <div x-show="step === 1" class="w-1"></div>

                <!-- Step controller triggers -->
                <div>
                    <!-- Welcome -> Step 2 -->
                    <button 
                        x-show="step === 1" 
                        @click="step = 2" 
                        class="btn-primary px-8 py-3 rounded-xl text-xs font-bold flex items-center gap-1.5 transition"
                    >
                        Start Setup
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Step 2 -> Step 3 -->
                    <button 
                        x-show="step === 2" 
                        @click="step = 3" 
                        :disabled="!hookGenerated"
                        :class="hookGenerated ? 'btn-primary' : 'bg-surface border border-border text-text/40 cursor-not-allowed opacity-50'"
                        class="btn px-8 py-3 rounded-xl text-xs font-bold flex items-center gap-1.5 transition"
                    >
                        Next Step
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Step 3 -> Step 4 -->
                    <button 
                        x-show="step === 3" 
                        @click="step = 4" 
                        :disabled="!savedToLibrary"
                        :class="savedToLibrary ? 'btn-primary' : 'bg-surface border border-border text-text/40 cursor-not-allowed opacity-50'"
                        class="btn px-8 py-3 rounded-xl text-xs font-bold flex items-center gap-1.5 transition"
                    >
                        Next Step
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Step 4 -> Step 5 -->
                    <button 
                        x-show="step === 4" 
                        @click="step = 5" 
                        class="btn-primary px-8 py-3 rounded-xl text-xs font-bold flex items-center gap-1.5 transition"
                    >
                        Complete Setup
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Form submission on Step 5 -->
                    <form 
                        x-show="step === 5" 
                        method="POST" 
                        action="{{ route('onboarding.complete') }}" 
                        class="inline-block"
                    >
                        @csrf
                        <button type="submit" class="btn-primary px-8 py-3 rounded-xl text-xs font-bold flex items-center gap-1.5 transition hover:shadow-primary/30">
                            Launch Factory Dashboard
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
