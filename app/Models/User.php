<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
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
}
