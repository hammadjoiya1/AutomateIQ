<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        if (in_array($event, ['subscription_created', 'subscription_updated'], true)) {
            if ($plan) {
                $user->plan = $plan;
            }
            if ($trialEnds) {
                $user->trial_ends_at = $trialEnds;
            }
            $user->save();
        }

        if (in_array($event, ['subscription_cancelled', 'subscription_expired'], true) || $status === 'cancelled') {
            $user->plan = 'free';
            $user->save();
        }
    }
}
