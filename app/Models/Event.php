<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'venue',
        'capacity',
        'available_slots',
        'start_date',
        'end_date',
        'registration_deadline',
        'organizer_id',
        'organizing_department',
        'status',
        'banner_image',
        'rulebook_file',
        'hashtags',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function activeRegistrations()
    {
        return $this->hasMany(Registration::class)->whereIn('status', ['registered', 'attended']);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class)->orderBy('position', 'asc');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class)->where('is_approved', true);
    }

    public function mediaGalleries()
    {
        return $this->hasMany(MediaGallery::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function isFull(): bool
    {
        return $this->available_slots <= 0;
    }

    public function averageRating(): float
    {
        return round($this->feedbacks()->avg('overall_rating') ?? 0, 1);
    }

    /**
     * Get the resolved banner URL or category fallback image.
     */
    public function getBannerUrlAttribute(): string
    {
        if (!empty($this->banner_image)) {
            // Check if banner_image is an external URL
            if (filter_var($this->banner_image, FILTER_VALIDATE_URL) || str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://')) {
                return $this->banner_image;
            }

            $cleanPath = ltrim($this->banner_image, '/\\');

            // Check if the file exists in public path
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }

            // Check if file exists inside uploads/events/
            $basename = basename($cleanPath);
            if (file_exists(public_path('uploads/events/' . $basename))) {
                return asset('uploads/events/' . $basename);
            }
        }

        // Return a high-quality category-tailored Unsplash banner
        return $this->getCategoryFallbackImage();
    }

    /**
     * High resolution curated campus imagery based on category & event context.
     */
    public function getCategoryFallbackImage(): string
    {
        $slug = $this->category ? $this->category->slug : '';
        $catName = strtolower($this->category ? $this->category->name : '');
        $title = strtolower($this->title ?? '');

        if (str_contains($slug, 'tech') || str_contains($slug, 'hackathon') || str_contains($slug, 'coding') || str_contains($catName, 'tech') || str_contains($catName, 'code') || str_contains($title, 'hack') || str_contains($title, 'code') || str_contains($title, 'robot')) {
            return 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1000&q=80';
        }

        if (str_contains($slug, 'sport') || str_contains($catName, 'sport') || str_contains($title, 'marathon') || str_contains($title, 'cricket') || str_contains($title, 'football') || str_contains($title, 'run') || str_contains($title, 'tournament')) {
            return 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1000&q=80';
        }

        if (str_contains($slug, 'cultural') || str_contains($catName, 'cultur') || str_contains($title, 'drama') || str_contains($title, 'dance') || str_contains($title, 'fest')) {
            return 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=1000&q=80';
        }

        if (str_contains($slug, 'music') || str_contains($catName, 'music') || str_contains($title, 'concert') || str_contains($title, 'band') || str_contains($title, 'singing')) {
            return 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1000&q=80';
        }

        if (str_contains($slug, 'game') || str_contains($slug, 'esport') || str_contains($catName, 'game') || str_contains($title, 'gaming') || str_contains($title, 'esport') || str_contains($title, 'lan')) {
            return 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1000&q=80';
        }

        if (str_contains($slug, 'workshop') || str_contains($slug, 'seminar') || str_contains($catName, 'workshop') || str_contains($catName, 'seminar') || str_contains($title, 'workshop') || str_contains($title, 'lecture')) {
            return 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1000&q=80';
        }

        if (str_contains($slug, 'career') || str_contains($slug, 'placement') || str_contains($catName, 'career') || str_contains($title, 'job') || str_contains($title, 'internship') || str_contains($title, 'fair')) {
            return 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=1000&q=80';
        }

        if (str_contains($slug, 'annual') || str_contains($catName, 'annual') || str_contains($title, 'annual') || str_contains($title, 'gala')) {
            return 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1000&q=80';
        }

        return 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1000&q=80';
    }
}
