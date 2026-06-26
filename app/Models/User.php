<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'theme',
        'credits',
        'role',
        'is_banned',
        'plan',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
            'last_credit_grant_at' => 'datetime',
        ];
    }
    public function toolRuns()
    {
        return $this->hasMany(ToolRun::class);
    }

    public function workflows()
    {
        return $this->hasMany(Workflow::class);
    }

    public function workflowRuns()
    {
        return $this->hasMany(WorkflowRun::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function libraryItems()
    {
        return $this->hasManyThrough(LibraryItem::class, Collection::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scripts()
    {
        return $this->hasMany(Script::class);
    }

    public function brandVoices()
    {
        return $this->hasMany(BrandVoice::class);
    }

    public function getActiveBrandVoiceAttribute()
    {
        return $this->brandVoices()->where('is_default', true)->first();
    }

    public function videoProjects()
    {
        return $this->hasMany(VideoProject::class);
    }

    public function favoriteTools()
    {
        return $this->belongsToMany(Tool::class, 'tool_favorites')->withTimestamps();
    }

    public function totalCredits(): int
    {
        return (int) $this->credits;
    }

    public function recomputeCredits(): void
    {
        $this->credits = (int) $this->subscription_credits + (int) $this->topup_credits;
    }

    public function grantSubscriptionCredits(int $amount): void
    {
        $this->subscription_credits = max(0, $amount);
        $this->last_credit_grant_at = now();
        $this->recomputeCredits();
        $this->save();
    }

    public function addTopupCredits(int $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->topup_credits = (int) $this->topup_credits + $amount;
        $this->recomputeCredits();
        $this->save();
    }

    public function removeTopupCredits(int $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->topup_credits = max(0, (int) $this->topup_credits - $amount);
        $this->recomputeCredits();
        $this->save();
    }

    public function debitCredits(int $amount): void
    {
        $amount = max(0, $amount);
        if ($amount === 0) {
            return;
        }

        $fromSubscription = min((int) $this->subscription_credits, $amount);
        $this->subscription_credits -= $fromSubscription;
        $remaining = $amount - $fromSubscription;

        if ($remaining > 0) {
            $this->topup_credits = max(0, (int) $this->topup_credits - $remaining);
        }

        $this->recomputeCredits();
        $this->save();
    }
}
