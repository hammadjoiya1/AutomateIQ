<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class LemonSqueezyWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Signature');
        $secret = config('lemonsqueezy.webhook_secret');

        if ($secret) {
            $computed = hash_hmac('sha256', $payload, $secret);
            if (!hash_equals($computed, (string) $signature)) {
                return response('Invalid signature', 400);
            }
        }

        $data = $request->json()->all();
        $event = $data['meta']['event_name'] ?? null;
        $attributes = $data['data']['attributes'] ?? [];
        $custom = $attributes['custom_data'] ?? [];
        $userId = $custom['user_id'] ?? null;

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $this->syncPlanFromEvent($user, $attributes, $event);
                $this->applyTopupCredits($user, $attributes, $event);
            }
        }

        return response('OK', 200);
    }

    protected function syncPlanFromEvent(User $user, array $attributes, ?string $event): void
    {
        $variantId = (string) ($attributes['variant_id'] ?? '');
        $status = $attributes['status'] ?? null;
        $trialEnds = $attributes['trial_ends_at'] ?? null;

        $plan = null;
        if ($variantId && $variantId === (string) config('lemonsqueezy.variant_ids.team')) {
            $plan = 'team';
        } elseif ($variantId && $variantId === (string) config('lemonsqueezy.variant_ids.pro')) {
            $plan = 'pro';
        }

        if (in_array($event, ['subscription_created', 'subscription_updated', 'subscription_renewed', 'subscription_resumed'], true)) {
            if ($plan) {
                $user->plan = $plan;
            }
            if ($trialEnds) {
                $user->trial_ends_at = $trialEnds;
            }
            $user->save();

            $this->grantMonthlyCreditsIfDue($user);
        }

        if (in_array($event, ['subscription_cancelled', 'subscription_expired'], true) || $status === 'cancelled') {
            $user->plan = 'free';
            $user->save();
        }
    }

    protected function grantMonthlyCreditsIfDue(User $user): void
    {
        $plan = $user->plan ?? 'free';
        $monthlyCredits = (int) (Setting::get("credits.monthly_credits.{$plan}", config("credits.monthly_credits.{$plan}")) ?? 0);

        if ($monthlyCredits <= 0) {
            return;
        }

        $lastGrant = $user->last_credit_grant_at;
        if ($lastGrant && $lastGrant instanceof Carbon && $lastGrant->isSameMonth(now())) {
            return;
        }

        $user->grantSubscriptionCredits($monthlyCredits);
    }

    protected function applyTopupCredits(User $user, array $attributes, ?string $event): void
    {
        if (!in_array($event, ['order_created', 'order_paid', 'order_refunded'], true)) {
            return;
        }

        $variantId = (string) (
            $attributes['variant_id']
                ?? ($attributes['first_order_item']['variant_id'] ?? null)
                ?? ($attributes['order_items'][0]['variant_id'] ?? null)
                ?? ''
        );

        if ($variantId === '') {
            return;
        }

        $topups = $this->topupVariantMap();
        $credits = (int) ($topups[$variantId] ?? 0);

        if ($credits <= 0) {
            return;
        }

        if ($event === 'order_refunded') {
            $user->removeTopupCredits($credits);
            return;
        }

        $user->addTopupCredits($credits);
    }

    protected function topupVariantMap(): array
    {
        $map = config('lemonsqueezy.topup_variants', []);

        foreach (['starter', 'growth', 'scale'] as $pack) {
            $variantId = Setting::get("lemonsqueezy.topup_variants.{$pack}.id", null);
            $credits = Setting::get("lemonsqueezy.topup_variants.{$pack}.credits", null);
            if ($variantId && $credits !== null && $credits !== '') {
                $map[(string) $variantId] = (int) $credits;
            }
        }

        return $map;
    }
}
