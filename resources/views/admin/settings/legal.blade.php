<x-admin-layout>
    @php
        $defaultPrivacy = '<p>At AutomateIQ, we believe that your creative content is your property. Our mission is to help you scale your reach without compromising your privacy. This policy explains how we handle your videos, audio, scripts, and account data.</p><h3>1. Content Ownership & AI Processing</h3><p>Any media (videos, podcasts, images) you upload to AutomateIQ remains strictly yours. We process this content using our AI models to generate clips, hooks, and scripts. <strong>We do not use your private content to train our foundational models</strong> without your explicit consent. Your data is processed securely and temporarily stored for your use.</p><h3>2. Information We Collect</h3><ul><li><strong>Account Data:</strong> Email, billing information, and platform preferences.</li><li><strong>Media Assets:</strong> Videos, audio files, and transcripts you upload or generate.</li><li><strong>Social Integration Data:</strong> Read/write tokens required to publish directly to your YouTube, TikTok, and Instagram accounts.</li></ul><h3>3. Social Media API Integrations</h3><p>When you connect your social media accounts, we request the minimum permissions necessary to automate your publishing workflow. We adhere strictly to the API terms of service for YouTube, Meta (Instagram/Facebook), and TikTok. You can revoke our access to these platforms at any time from your dashboard.</p><h3>4. Data Security & Retention</h3><p>All data in transit and at rest is protected using industry-standard encryption. Generated assets and raw footage are stored securely in isolated buckets. You can delete your projects at any time, which permanently removes the associated media from our servers.</p><h3>5. Third-Party Services</h3><p>We may share necessary data with trusted third parties solely to provide our services (e.g., payment processors like Stripe, or secure cloud storage providers). We do not sell your personal data or your content to advertisers or data brokers.</p>';

        $defaultTerms = '<p>By using AutomateIQ, you agree to these Terms of Service. Please read them carefully.</p><h3>1. Usage Rights & Content Ownership</h3><p>You retain full ownership of the original raw footage and audio you upload to AutomateIQ. You also own the rights to any AI-generated clips, hooks, and scripts produced by our platform on your behalf, provided you have an active, valid subscription.</p><h3>2. Acceptable Use Policy</h3><p>Our tools are designed to automate your content distribution safely and efficiently. You may not use AutomateIQ to:</p><ul><li>Generate or distribute illegal, hateful, explicit, or harmful content.</li><li>Automate spam bots or violate the Terms of Service of third-party platforms (YouTube, TikTok, Meta).</li><li>Attempt to bypass our API rate limits or rendering constraints.</li></ul><p>We reserve the right to suspend or permanently ban accounts found violating this policy without refund.</p><h3>3. Social Platform Compliance & Liability</h3><p>While AutomateIQ schedules and posts content using official APIs, <strong>we are not responsible for any copyright strikes, shadowbans, or account suspensions</strong> you may receive on third-party sites. It is your responsibility to ensure the content you generate and distribute complies with the rules of the destination platforms.</p><h3>4. Rendering Constraints & SLAs</h3><p>Video rendering times depend on your subscription tier and the complexity of your pipeline. While we strive for high availability and fast processing (e.g., 4K priority queues for Pro/Enterprise users), we do not guarantee specific turnaround times during periods of extraordinary demand unless specified in a custom Enterprise Service Level Agreement (SLA).</p>';
    @endphp
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Legal Pages</h1>
    </div>

    <div class="card p-6 border border-border">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-text mb-1">Privacy Policy HTML</label>
                <input id="privacy_policy_content" type="hidden" name="privacy_policy_content" value="{{ !empty($settings['privacy_policy_content']) ? $settings['privacy_policy_content'] : $defaultPrivacy }}">
                <trix-editor input="privacy_policy_content" class="trix-content bg-surface border border-border rounded-lg text-text min-h-[300px] prose-strat max-w-none mt-2"></trix-editor>
                <p class="text-xs text-text-muted mt-2">Leave blank to use the default policy.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-text mb-1">Terms of Service HTML</label>
                <input id="terms_of_service_content" type="hidden" name="terms_of_service_content" value="{{ !empty($settings['terms_of_service_content']) ? $settings['terms_of_service_content'] : $defaultTerms }}">
                <trix-editor input="terms_of_service_content" class="trix-content bg-surface border border-border rounded-lg text-text min-h-[300px] prose-strat max-w-none mt-2"></trix-editor>
                <p class="text-xs text-text-muted mt-2">Leave blank to use the default terms.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-primary w-full justify-center">Save Legal Pages</button>
            </div>
        </form>
    </div>
</x-admin-layout>
