<x-public-layout meta-title="Terms of Service — AutomateIQ">
    <div class="py-24 sm:py-32 relative">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-16">
                <div class="section-badge mb-4 inline-block">📋 Legal</div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text">Terms of Service</h1>
            </div>
            
            <div class="strat-card scroll-reveal p-8 md:p-12 prose-strat">
                @php
                    $customTerms = \DB::table('settings')->where('key', 'terms_of_service_content')->value('value');
                @endphp

                @if(!empty($customTerms))
                    {!! $customTerms !!}
                @else
                    <p>Last updated: {{ date('F d, Y') }}</p>
                    <p>By using AutomateIQ, you agree to these Terms of Service. Please read them carefully.</p>

                    <h3>1. Usage Rights & Content Ownership</h3>
                    <p>You retain full ownership of the original raw footage and audio you upload to AutomateIQ. You also own the rights to any AI-generated clips, hooks, and scripts produced by our platform on your behalf, provided you have an active, valid subscription.</p>

                    <h3>2. Acceptable Use Policy</h3>
                    <p>Our tools are designed to automate your content distribution safely and efficiently. You may not use AutomateIQ to:</p>
                    <ul>
                        <li>Generate or distribute illegal, hateful, explicit, or harmful content.</li>
                        <li>Automate spam bots or violate the Terms of Service of third-party platforms (YouTube, TikTok, Meta).</li>
                        <li>Attempt to bypass our API rate limits or rendering constraints.</li>
                    </ul>
                    <p>We reserve the right to suspend or permanently ban accounts found violating this policy without refund.</p>

                    <h3>3. Social Platform Compliance & Liability</h3>
                    <p>While AutomateIQ schedules and posts content using official APIs, <strong>we are not responsible for any copyright strikes, shadowbans, or account suspensions</strong> you may receive on third-party sites. It is your responsibility to ensure the content you generate and distribute complies with the rules of the destination platforms.</p>

                    <h3>4. Rendering Constraints & SLAs</h3>
                    <p>Video rendering times depend on your subscription tier and the complexity of your pipeline. While we strive for high availability and fast processing (e.g., 4K priority queues for Pro/Enterprise users), we do not guarantee specific turnaround times during periods of extraordinary demand unless specified in a custom Enterprise Service Level Agreement (SLA).</p>
                @endif
            </div>
        </div>
    </div>
</x-public-layout>