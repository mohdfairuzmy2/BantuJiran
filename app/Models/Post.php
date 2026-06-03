<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public const TYPES = ['sos', 'donate', 'tool', 'mobility', 'group_buy'];

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'category',
        'status',
        'severity',
        'price',
        'lat',
        'lng',
        'radius_km',
        'meta',
        'helper_id',
        'expires_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'price' => 'float',
        'radius_km' => 'float',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function helper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'helper_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Filter & sort posts within a radius (km) of a coordinate using
     * MySQL ST_Distance_Sphere on the generated spatial column. Adds a
     * `distance_m` attribute (metres) to each result.
     */
    public function scopeWithinRadius(Builder $query, float $lat, float $lng, float $km): Builder
    {
        $point = sprintf('ST_SRID(POINT(%F, %F), 4326)', $lng, $lat);

        return $query
            ->select('posts.*')
            ->selectRaw("ST_Distance_Sphere(location, {$point}) AS distance_m")
            ->whereRaw("ST_Distance_Sphere(location, {$point}) <= ? * 1000", [$km])
            ->orderBy('distance_m');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['open', 'in_progress'])
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function getDistanceLabelAttribute(): ?string
    {
        if (! isset($this->attributes['distance_m'])) {
            return null;
        }
        $m = (float) $this->attributes['distance_m'];
        return $m < 1000
            ? round($m) . ' m'
            : number_format($m / 1000, 1) . ' km';
    }

    public static function typeMeta(string $type): array
    {
        return [
            'sos' => ['label' => 'Mohon Bantuan', 'icon' => 'bi-exclamation-octagon-fill', 'color' => 'danger'],
            'donate' => ['label' => 'Derma Barang', 'icon' => 'bi-box2-heart-fill', 'color' => 'teal'],
            'tool' => ['label' => 'Perkongsian Peralatan', 'icon' => 'bi-tools', 'color' => 'purple'],
            'mobility' => ['label' => 'Mobiliti & Tumpangan', 'icon' => 'bi-car-front-fill', 'color' => 'primary'],
            'group_buy' => ['label' => 'Pengiklanan Perkhidmatan/Barangan', 'icon' => 'bi-bag-fill', 'color' => 'warning'],
        ][$type] ?? ['label' => $type, 'icon' => 'bi-pin-map', 'color' => 'secondary'];
    }

    public function meta(): array
    {
        return self::typeMeta($this->type);
    }

    public function isSensitive(): bool
    {
        return false;
    }
}
