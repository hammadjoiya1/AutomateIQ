/**
 * terminal-sim.js
 * Simulates a realistic interactive developer terminal session typewriter stream.
 */
export function initTerminalSim(container) {
    if (!container) return () => {};

    const sequences = [
        [
            { text: 'aiq workflow:deploy --pipeline=production', type: 'command' },
            { text: '🔍 Scanning project files...', type: 'info', delay: 400 },
            { text: '✔ Found 12 custom tools, 3 flow presets', type: 'success', delay: 200 },
            { text: '📦 Bundling dependency tree...', type: 'info', delay: 500 },
            { text: '   -> lodash-es, ogl, alpinejs, post-processing-v2', type: 'muted', delay: 100 },
            { text: '⚡ Connecting to cluster: clusters.automateiq.com', type: 'info', delay: 400 },
            { text: '🚀 Uploading container assets...', type: 'info', delay: 300 },
            { text: '   [||||||||||||||||||||] 100% complete', type: 'progress', delay: 600 },
            { text: '✨ Launching workflow executor runtime...', type: 'success', delay: 300 },
            { text: '✔ Deployment successful. Active nodes URL: https://aiq.run/a9f82d', type: 'success', delay: 200 },
            { text: '📡 Live telemetry active (SLA response time < 1.2s)', type: 'accent', delay: 400 }
        ],
        [
            { text: 'aiq agent:run --prompt="monitor server resources and trigger alert"', type: 'command' },
            { text: '🤖 Initializing autonomous agent backend...', type: 'info', delay: 500 },
            { text: '🔑 Loaded OpenTelemetry API keys', type: 'success', delay: 200 },
            { text: '👁 Analyzing signal chain logs...', type: 'info', delay: 400 },
            { text: '⚠️ Alert: CPU spike detected on node 4 (89.2% load)', type: 'warning', delay: 600 },
            { text: '🔄 Executing workflow action: scale-replicas-up', type: 'info', delay: 400 },
            { text: '   -> Dispatched payload to AWS ECS cluster', type: 'muted', delay: 200 },
            { text: '✔ Replica scaled. Load stabilized to 41.5%', type: 'success', delay: 600 },
            { text: '🤖 Agent entering sleep mode. Listening to port 8000...', type: 'info', delay: 400 }
        ]
    ];

    let currentSeqIdx = 0;
    let timeoutId = null;
    let active = true;

    function runSequence() {
        if (!active) return;
        container.innerHTML = '';
        const seq = sequences[currentSeqIdx];
        let stepIdx = 0;

        function printNextStep() {
            if (!active) return;
            if (stepIdx >= seq.length) {
                // Pause before next sequence
                timeoutId = setTimeout(() => {
                    currentSeqIdx = (currentSeqIdx + 1) % sequences.length;
                    runSequence();
                }, 4500);
                return;
            }

            const step = seq[stepIdx];
            const line = document.createElement('div');
            line.className = getLineClass(step.type);
            container.appendChild(line);

            if (step.type === 'command') {
                // Typewriter effect for command prompt
                const prefix = document.createElement('span');
                prefix.className = 'text-primary mr-2 select-none';
                prefix.textContent = '>';
                line.appendChild(prefix);

                const cmdText = document.createElement('span');
                line.appendChild(cmdText);

                let charIdx = 0;
                function typeChar() {
                    if (!active) return;
                    if (charIdx < step.text.length) {
                        cmdText.textContent += step.text[charIdx];
                        charIdx++;
                        timeoutId = setTimeout(typeChar, 40 + Math.random() * 30);
                    } else {
                        // Done typing command, run next step
                        stepIdx++;
                        timeoutId = setTimeout(printNextStep, 500);
                    }
                }
                typeChar();
            } else {
                // Instantly print other lines after a delay
                line.textContent = step.text;
                stepIdx++;
                timeoutId = setTimeout(printNextStep, step.delay || 300);
            }
            
            // Auto scroll container
            container.scrollTop = container.scrollHeight;
        }

        printNextStep();
    }

    function getLineClass(type) {
        switch (type) {
            case 'command': return 'flex items-center text-white font-semibold leading-relaxed';
            case 'success': return 'text-green-400 font-medium leading-relaxed';
            case 'warning': return 'text-yellow-500 font-medium leading-relaxed';
            case 'muted': return 'text-white/40 leading-relaxed';
            case 'accent': return 'text-primary font-medium leading-relaxed';
            case 'progress': return 'text-purple-400 font-mono leading-relaxed';
            default: return 'text-white/70 leading-relaxed';
        }
    }

    // Start simulation
    runSequence();

    return function cleanup() {
        active = false;
        if (timeoutId) clearTimeout(timeoutId);
    };
}
