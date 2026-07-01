<x-public-layout meta-title="Privacy Policy — AutomateIQ">
    <div class="py-24 sm:py-32 relative">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-16">
                <div class="section-badge mb-4 inline-block">🔒 Legal</div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text">Privacy Policy</h1>
            </div>
            
            <div class="strat-card scroll-reveal p-8 md:p-12 prose-strat">
                @php
                    $customPrivacy = \DB::table('settings')->where('key', 'privacy_policy_content')->value('value');
                @endphp

                @if(!empty($customPrivacy))
                    {!! $customPrivacy !!}
                @else
                    <p>Last updated: {{ date('F d, Y') }}</p>
                    <p>At AutomateIQ, we believe that your creative content is your property. Our mission is to help you scale your reach without compromising your privacy. This policy explains how we handle your videos, audio, scripts, and account data.</p>

                    <h3>1. Content Ownership & AI Processing</h3>
                    <p>Any media (videos, podcasts, images) you upload to AutomateIQ remains strictly yours. We process this content using our AI models to generate clips, hooks, and scripts. <strong>We do not use your private content to train our foundational models</strong> without your explicit consent. Your data is processed securely and temporarily stored for your use.</p>

                    <h3>2. Information We Collect</h3>
                    <ul>
                        <li><strong>Account Data:</strong> Email, billing information, and platform preferences.</li>
                        <li><strong>Media Assets:</strong> Videos, audio files, and transcripts you upload or generate.</li>
                        <li><strong>Social Integration Data:</strong> Read/write tokens required to publish directly to your YouTube, TikTok, and Instagram accounts.</li>
                    </ul>

                    <h3>3. Social Media API Integrations</h3>
                    <p>When you connect your social media accounts, we request the minimum permissions necessary to automate your publishing workflow. We adhere strictly to the API terms of service for YouTube, Meta (Instagram/Facebook), and TikTok. You can revoke our access to these platforms at any time from your dashboard.</p>

                    <h3>4. Data Security & Retention</h3>
                    <p>All data in transit and at rest is protected using industry-standard encryption. Generated assets and raw footage are stored securely in isolated buckets. You can delete your projects at any time, which permanently removes the associated media from our servers.</p>

                    <h3>5. Third-Party Services</h3>
                    <p>We may share necessary data with trusted third parties solely to provide our services (e.g., payment processors like Stripe, or secure cloud storage providers). We do not sell your personal data or your content to advertisers or data brokers.</p>
                @endif
            </div>
        </div>
    </div>
</x-public-layout>