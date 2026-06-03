<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','phone','phone_verified_at','email','password',
        'is_verified','verified_at','trust_score','reviews_count',
        'home_lat','home_lng','address_label','avatar',
        'is_admin','onboarded_at','is_banned',
    ];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'onboarded_at' => 'datetime',
            'is_verified' => 'boolean',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
            'trust_score' => 'float',
            'home_lat' => 'float',
            'home_lng' => 'float',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany { return $this->hasMany(Post::class); }
    public function reviewsReceived(): HasMany { return $this->hasMany(Review::class,'reviewee_id'); }
    public function pushSubscriptions(): HasMany { return $this->hasMany(PushSubscription::class); }

    public function hasLocation(): bool { return $this->home_lat !== null && $this->home_lng !== null; }
    public function hasOnboarded(): bool { return $this->onboarded_at !== null; }
    public function avatarUrl(): string {
        return $this->avatar ? asset('storage/'.$this->avatar) : '';
    }

    public function recalculateTrustScore(): void {
        $stats = $this->reviewsReceived()->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')->first();
        $this->forceFill([
            'trust_score' => round((float)($stats->avg_rating ?? 0), 2),
            'reviews_count' => (int)($stats->total ?? 0),
        ])->save();
    }

    public function trustBadge(): string {
        if ($this->reviews_count >= 5 && $this->trust_score >= 4.5) return 'Jiran Emas';
        if ($this->reviews_count >= 1 && $this->trust_score >= 4.0) return 'Jiran Dipercayai';
        return $this->is_verified ? 'Disahkan' : 'Jiran Baru';
    }

    public function unreadChats(): int {
        return \App\Models\Message::whereHas('conversation', fn($q) =>
            $q->where('user_one_id', $this->id)->orWhere('user_two_id', $this->id)
        )->where('user_id', '!=', $this->id)->whereNull('read_at')->count();
    }
}
