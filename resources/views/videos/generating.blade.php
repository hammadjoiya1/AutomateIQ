<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creation in progress</title>
</head>
<body style="background-color: var(--color-bg); color: var(--color-text); margin: 0; overflow: hidden; font-family: sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; background-color: rgba(var(--primary-rgb), 0.1); filter: blur(120px); border-radius: 50%; pointer-events: none;"></div>

    <div style="position: relative; z-index: 10; width: 100%; max-width: 48rem; padding: 0 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
        
        {{-- Spinner --}}
        <div style="margin-bottom: 2rem; width: 3rem; height: 3rem; position: relative;">
            <svg style="animation: spin 1s linear infinite; width: 100%; height: 100%; color: var(--color-border);" viewBox="0 0 24 24" fill="none">
                <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                <path d="M12 2a10 10 0 0 1 10 10" stroke="var(--primary-light)" stroke-width="2" stroke-linecap="round"></path>
            </svg>
        </div>

        {{-- Title --}}
        <h1 style="font-size: 3rem; margin-bottom: 1rem; color: var(--color-text); font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif; font-style: italic; letter-spacing: -1px;">Creation in progress</h1>

        {{-- Progress Text --}}
        <h2 style="font-size: 1.875rem; font-weight: 800; color: var(--color-text); margin-bottom: 0.5rem; letter-spacing: 0.025em;" id="hero-progress-percent">0%</h2>
        <p style="color: var(--color-text-muted); margin-bottom: 3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.875rem;" id="hero-progress-text">Initializing...</p>

        {{-- Steps Cards --}}
        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; width: 100%; margin-bottom: 3rem;">
            <div id="step-script" style="background-color: rgba(var(--primary-rgb), 0.1); border: 1px solid rgba(var(--primary-rgb), 0.3); border-radius: 1rem; padding: 2rem 1rem; box-shadow: 0 0 15px rgba(var(--primary-rgb), 0.1); transition: all 0.5s;">
                <h3 style="color: var(--color-text); font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;">Script</h3>
            </div>
            <div id="step-assets" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: 1rem; padding: 2rem 1rem; transition: all 0.5s;">
                <h3 style="color: var(--color-text-muted); font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;">Assets</h3>
            </div>
            <div id="step-editing" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: 1rem; padding: 2rem 1rem; transition: all 0.5s;">
                <h3 style="color: var(--color-text-muted); font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;">Editing</h3>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div style="width: 100%; max-width: 42rem; background-color: var(--color-surface); border-radius: 9999px; height: 0.75rem; overflow: hidden; border: 1px solid var(--color-border); position: relative;">
            <div id="hero-progress-bar" style="height: 100%; border-radius: 9999px; transition: width 0.3s ease-out; background-image: var(--gradient-primary); width: 5%;">
            </div>
        </div>
    </div>

    <script>
        function initPolling() {
            const pollInterval = setInterval(checkStatus, 5000); // Poll every 5 seconds
            
            // Persistent simulated progress state
            const storageKey = 'progress_project_{{ $project->id }}';
            let simulatedProgress = 5;
            try {
                simulatedProgress = parseFloat(localStorage.getItem(storageKey)) || 5;
            } catch (e) {}
            
            const maxSimulated = 90;
            const duration = 120; // Approx 2 minutes per scene
            const increment = (maxSimulated - 5) / (duration / 5); // Increment per 5s
 
            function checkStatus() {
                try {
                    // Update simulated progress locally first
                    if (simulatedProgress < maxSimulated) {
                        simulatedProgress += increment;
                        try {
                            localStorage.setItem(storageKey, simulatedProgress);
                        } catch (e) {} // Ignore localStorage errors
                    }
                    
                    // Immediately update UI with simulated progress so it doesn't freeze while waiting for the server
                    updateUI(Math.floor(simulatedProgress), "Processing...");
 
                    fetch('{{ route('videos.check-status', $project) }}')
                        .then(response => response.json())
                        .then(data => {
                        let displayPercent = Math.floor(simulatedProgress);
                        let displayStatus = "Processing...";
 
                        // If we have multiple scenes, calculate real mathematical progress
                        if (data.total_scenes && data.total_scenes > 0) {
                            const chunkPerScene = 100 / data.total_scenes;
                            const baseProgress = data.completed_scenes * chunkPerScene;
                            
                            // Add the simulated progress of the *current* scene
                            const currentSceneProgress = (simulatedProgress / 100) * chunkPerScene;
                            
                            displayPercent = Math.floor(baseProgress + currentSceneProgress);
                            displayStatus = `Rendering Scene ${data.completed_scenes + 1} of ${data.total_scenes}...`;
                            
                            if (data.completed_scenes >= data.total_scenes) {
                                displayPercent = 95;
                                displayStatus = "Stitching final video...";
                            }
                        } else {
                            displayStatus = data.status === 'generating' ? "Generating frames..." : "Processing...";
                        }
                        
                        // Prevent progress from going backwards
                        const highestProgressKey = 'highest_progress_{{ $project->id }}';
                        let highestProgress = parseInt(localStorage.getItem(highestProgressKey)) || 0;
                        if (displayPercent > highestProgress) {
                            highestProgress = displayPercent;
                            localStorage.setItem(highestProgressKey, highestProgress);
                        } else {
                            displayPercent = highestProgress;
                        }
 
                        // Dynamic DOM Replacement on scene completion
                        if (window.lastCompletedScenes === undefined) {
                            window.lastCompletedScenes = data.completed_scenes;
                        } else if (data.completed_scenes !== undefined && data.completed_scenes > window.lastCompletedScenes) {
                            window.lastCompletedScenes = data.completed_scenes;
                            
                            // A scene finished! Reset simulation for next scene
                            simulatedProgress = 5;
                            localStorage.setItem(storageKey, simulatedProgress);
                        }
 
                        // Update UI
                        updateUI(displayPercent, displayStatus);
 
                        if (data.status === 'completed') {
                            updateUI(100, "Finalizing...");
                            localStorage.removeItem(storageKey);
                            localStorage.removeItem(highestProgressKey);
                            clearInterval(pollInterval);
                            
                            // Final dynamic load for the completed stitched video
                            window.location.reload();
                        } else if (data.status === 'failed') {
                            clearInterval(pollInterval);
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error polling status:', error);
                        const statusText = document.getElementById('hero-progress-text');
                        if (statusText) statusText.innerText = 'Network error... Retrying...';
                    });
                } catch (e) {
                    console.error('JS Error in checkStatus:', e);
                    const statusText = document.getElementById('hero-progress-text');
                    if (statusText) statusText.innerText = 'JS Error: ' + e.message;
                }
            }
            
            function updateUI(percent, text) {
                // Hero UI Elements
                const heroBar = document.getElementById('hero-progress-bar');
                const heroPercentText = document.getElementById('hero-progress-percent');
                const heroStatusText = document.getElementById('hero-progress-text');
                
                if (heroBar) heroBar.style.width = percent + '%';
                if (heroPercentText) heroPercentText.innerText = percent + '%';
                if (heroStatusText && text) heroStatusText.innerText = text;
 
                // Hero Steps UI
                const stepScript = document.getElementById('step-script');
                const stepAssets = document.getElementById('step-assets');
                const stepEditing = document.getElementById('step-editing');
 
                if (stepScript && stepAssets && stepEditing) {
                    const activeColor = "var(--color-accent)";
                    const activeBg = "rgba(var(--primary-rgb), 0.1)";
                    const activeBorder = "1px solid rgba(var(--primary-rgb), 0.5)";
                    const activeShadow = "0 0 15px rgba(var(--primary-rgb), 0.2)";
                    
                    const activeStyle = `background-color: ${activeBg}; border: ${activeBorder}; border-radius: 1rem; padding: 2rem 1rem; box-shadow: ${activeShadow}; transition: all 0.5s;`;
                    const activeTextStyle = `color: var(--color-text); font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;`;
                    
                    const inactiveStyle = "background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: 1rem; padding: 2rem 1rem; transition: all 0.5s;";
                    const inactiveTextStyle = "color: var(--color-text-muted); font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;";
 
                    if (percent < 30) {
                        stepScript.style.cssText = activeStyle;
                        stepScript.querySelector('h3').style.cssText = activeTextStyle;
                        stepAssets.style.cssText = inactiveStyle;
                        stepAssets.querySelector('h3').style.cssText = inactiveTextStyle;
                        stepEditing.style.cssText = inactiveStyle;
                        stepEditing.querySelector('h3').style.cssText = inactiveTextStyle;
                    } else if (percent < 90) {
                        stepScript.style.cssText = inactiveStyle;
                        stepScript.querySelector('h3').style.cssText = inactiveTextStyle;
                        
                        stepAssets.style.cssText = `background-color: ${activeBg}; border: ${activeBorder}; border-radius: 1rem; padding: 2rem 1rem; box-shadow: ${activeShadow}; transition: all 0.5s;`;
                        stepAssets.querySelector('h3').style.cssText = activeTextStyle;
 
                        stepEditing.style.cssText = inactiveStyle;
                        stepEditing.querySelector('h3').style.cssText = inactiveTextStyle;
                    } else {
                        stepScript.style.cssText = inactiveStyle;
                        stepScript.querySelector('h3').style.cssText = inactiveTextStyle;
                        stepAssets.style.cssText = inactiveStyle;
                        stepAssets.querySelector('h3').style.cssText = inactiveTextStyle;
 
                        stepEditing.style.cssText = `background-color: ${activeBg}; border: ${activeBorder}; border-radius: 1rem; padding: 2rem 1rem; box-shadow: ${activeShadow}; transition: all 0.5s;`;
                        stepEditing.querySelector('h3').style.cssText = activeTextStyle;
                    }
                }
            }

            // Run once immediately
            checkStatus();
        }

        // Execute immediately since the script is at the bottom of the body
        initPolling();
    </script>
</body>
</html>
