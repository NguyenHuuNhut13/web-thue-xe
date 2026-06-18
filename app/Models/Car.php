<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $appends = [
        'thumbnail_url',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'brand',
        'model',
        'year',
        'fuel_type',
        'transmission',
        'seats',
        'price_per_day',
        'description',
        'images',
        'location',
        'latitude',
        'longitude',
        'status',
        'has_driver',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'price_per_day' => 'decimal:2',
            'latitude' => 'double',
            'longitude' => 'double',
            'has_driver' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saving(function ($car) {
            if ($car->location && (empty($car->latitude) || empty($car->longitude))) {
                try {
                    $address = $car->location;
                    $opts = [
                        'http' => [
                            'method' => 'GET',
                            'header' => [
                                'User-Agent: NKS-Car-Sharing-App/1.0 (system@nks.vn)'
                            ],
                            'timeout' => 2.0
                        ]
                    ];
                    $context = stream_context_create($opts);
                    $response = @file_get_contents(
                        "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json&limit=1",
                        false,
                        $context
                    );
                    
                    if ($response) {
                        $data = json_decode($response, true);
                        if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                            $car->latitude = (double) $data[0]['lat'];
                            $car->longitude = (double) $data[0]['lon'];
                        }
                    }
                } catch (\Exception $e) {
                    // Fail silently
                }
                
                // Fallback to center of HCMC if still empty
                if (empty($car->latitude) || empty($car->longitude)) {
                    $car->latitude = 10.7769;
                    $car->longitude = 106.7009;
                }
            }
        });
    }

    public function getThumbnailUrlAttribute()
    {
        $default = 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
        if (empty($this->images) || !is_array($this->images) || count($this->images) === 0) {
            return $default;
        }

        $img = $this->images[0];
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }

        return '/storage/' . $img;
    }

    public function imageUrl($index = 0)
    {
        $default = 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
        if (empty($this->images) || !is_array($this->images) || count($this->images) <= $index) {
            return $default;
        }

        $img = $this->images[$index];
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }

        return '/storage/' . $img;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
