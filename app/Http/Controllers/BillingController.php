<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Setting;

class BillingController extends Controller
{
    public function checkout(Request $request, string $plan): RedirectResponse
    {
        $user = $request->user();

        $checkoutUrl = $this->checkoutUrlForPlan($plan);
        if (!$checkoutUrl) {
            return redirect()->route('pricing')->with('error', 'Billing is not configured.');
        }

        $params = http_build_query([
            'checkout[custom][user_id]' => $user->id,
            'checkout[custom][email]' => $user->email,
            'checkout[custom][plan]' => $plan,
            'checkout[custom][redirect]' => route('billing.success'),
        ]);

        $separator = str_contains($checkoutUrl, '?') ? '&' : '?';
        return redirect()->away($checkoutUrl . $separator . $params);
    }

    public function portal(Request $request): RedirectResponse
    {
        $portalUrl = config('lemonsqueezy.portal_url');
        if (!$portalUrl) {
            return redirect()->route('pricing')->with('error', 'Billing portal is not configured.');
        }

        return redirect()->away($portalUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard')->with('success', 'Subscription active.');
    }

    public function topup(Request $request, string $pack): RedirectResponse
    {
        $checkoutUrl = $this->topupCheckoutUrl($pack);
        if (!$checkoutUrl) {
            return redirect()->route('pricing')->with('error', 'Top-up checkout is not configured.');
        }

        $params = http_build_query([
            'checkout[custom][user_id]' => $request->user()->id,
            'checkout[custom][email]' => $request->user()->email,
            'checkout[custom][plan]' => 'topup',
            'checkout[custom][redirect]' => route('billing.success'),
        ]);

        $separator = str_contains($checkoutUrl, '?') ? '&' : '?';
        return redirect()->away($checkoutUrl . $separator . $params);
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('pricing')->with('error', 'Checkout canceled.');
    }

    protected function checkoutUrlForPlan(string $plan): ?string
    {
        return match ($plan) {
            'pro' => config('lemonsqueezy.checkout_urls.pro'),
            'team' => config('lemonsqueezy.checkout_urls.team'),
            'pro-annual' => config('lemonsqueezy.checkout_urls_annual.pro'),
            'team-annual' => config('lemonsqueezy.checkout_urls_annual.team'),
            default => null,
        };
    }

    protected function topupCheckoutUrl(string $pack): ?string
    {
        $settingKey = "lemonsqueezy.topup_checkout_urls.{$pack}";
        $settingUrl = Setting::get($settingKey, null);
        if ($settingUrl) {
            return $settingUrl;
        }

        $urls = config('lemonsqueezy.topup_checkout_urls', []);
        return $urls[$pack] ?? null;
    }
}
