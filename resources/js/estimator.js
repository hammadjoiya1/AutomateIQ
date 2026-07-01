/**
 * estimator.js
 * Drives the interactive agency-style AI Pipeline & SLA Cost Estimator.
 */
export function initEstimator() {
    const volumeInput = document.getElementById('estimator-volume');
    const volumeLabel = document.getElementById('estimator-volume-label');
    const costOutput  = document.getElementById('estimator-cost-output');
    const runsOutput  = document.getElementById('estimator-runs-output');
    const supportOutput = document.getElementById('estimator-support-output');
    const clusterOutput = document.getElementById('estimator-cluster-output');

    if (!volumeInput) return () => {};

    // Get complexity options
    const complexityButtons = document.querySelectorAll('[data-estimator-complexity]');
    let activeComplexity = 'advanced'; // default

    // Get support options
    const supportButtons = document.querySelectorAll('[data-estimator-support]');
    let activeSupport = 'business'; // default

    function calculate() {
        const volume = parseInt(volumeInput.value, 10);
        
        // Formatter helper
        volumeLabel.textContent = volume.toLocaleString() + ' Runs';

        // 1. Calculate Complexity Multiplier
        let complexityMult = 1.0;
        if (activeComplexity === 'simple') complexityMult = 0.6;
        if (activeComplexity === 'advanced') complexityMult = 1.5;
        if (activeComplexity === 'enterprise') complexityMult = 3.5;

        // 2. Compute base cost and usage fee
        // First 10,000 runs are free/included in base fee of $29
        const baseCost = 29;
        let usageFee = 0;
        if (volume > 10000) {
            // $0.0018 per run for simple, scaling with complexity multiplier
            usageFee = (volume - 10000) * 0.0018 * complexityMult;
        }

        // 3. Compute Support Flat Fee
        let supportFee = 0;
        if (activeSupport === 'business') supportFee = 99;
        if (activeSupport === 'enterprise') supportFee = 399;

        // 4. Sum Total
        const total = baseCost + usageFee + supportFee;

        // 5. Update UI values
        costOutput.textContent = '$' + Math.round(total).toLocaleString();
        runsOutput.textContent = '$' + Math.round(baseCost + usageFee).toLocaleString() + '/mo';
        supportOutput.textContent = '$' + supportFee.toLocaleString() + '/mo';

        // 6. Cluster Status Tag
        if (volume < 50000) {
            clusterOutput.textContent = 'Shared Edge Node';
            clusterOutput.className = 'px-3 py-1 border border-white/10 bg-white/5 rounded-full text-[10px] font-mono font-semibold text-white/50';
        } else if (volume < 500000) {
            clusterOutput.textContent = 'Dedicated VPC Instance';
            clusterOutput.className = 'px-3 py-1 border border-purple-500/20 bg-purple-500/10 rounded-full text-[10px] font-mono font-semibold text-purple-300';
        } else {
            clusterOutput.textContent = 'High-Availability Multi-Region';
            clusterOutput.className = 'px-3 py-1 border border-green-500/20 bg-green-500/10 rounded-full text-[10px] font-mono font-semibold text-green-300 animate-pulse';
        }
    }

    // Complexity button listeners
    complexityButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            complexityButtons.forEach((b) => b.classList.remove('active', 'border-primary', 'bg-primary/10', 'text-white'));
            complexityButtons.forEach((b) => b.classList.add('border-white/5', 'text-white/60'));
            
            btn.classList.add('active', 'border-primary', 'bg-primary/10', 'text-white');
            btn.classList.remove('border-white/5', 'text-white/60');
            
            activeComplexity = btn.getAttribute('data-estimator-complexity');
            calculate();
        });
    });

    // Support button listeners
    supportButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            supportButtons.forEach((b) => b.classList.remove('active', 'border-primary', 'bg-primary/10', 'text-white'));
            supportButtons.forEach((b) => b.classList.add('border-white/5', 'text-white/60'));

            btn.classList.add('active', 'border-primary', 'bg-primary/10', 'text-white');
            btn.classList.remove('border-white/5', 'text-white/60');

            activeSupport = btn.getAttribute('data-estimator-support');
            calculate();
        });
    });

    // Input slider listener
    volumeInput.addEventListener('input', calculate);

    // Initial calculation
    calculate();

    return function cleanup() {
        volumeInput.removeEventListener('input', calculate);
    };
}
