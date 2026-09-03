<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    use HasFactory;

    protected $table = 'media_galleries';

    protected $fillable = [
        'event_id',
        'title',
        'media_type',
        'file_path',
        'category',
        'department',
        'year',
        'uploaded_by',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedMedia::class, 'media_id');
    }

    /**
     * Get the resolved media URL or fallback image.
     */
    public function getFileUrlAttribute(): string
    {
        if (!empty($this->file_path)) {
            if (filter_var($this->file_path, FILTER_VALIDATE_URL) || str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
                return $this->file_path;
            }

            $cleanPath = ltrim($this->file_path, '/\\');
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }

            $basename = basename($cleanPath);
            if (file_exists(public_path('uploads/gallery/' . $basename))) {
                return asset('uploads/gallery/' . $basename);
            }
        }

        return $this->getFallbackImage();
    }

    /**
     * Category-tailored fallback image for media gallery items.
     */
    public function getFallbackImage(): string
    {
        $cat = strtolower($this->category ?? '');
        $title = strtolower($this->title ?? '');

        if (str_contains($cat, 'tech') || str_contains($cat, 'code') || str_contains($title, 'hack') || str_contains($title, 'robot')) {
            return 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&q=80';
        }

        if (str_contains($cat, 'sport') || str_contains($title, 'sport') || str_contains($title, 'match') || str_contains($title, 'race')) {
            return 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&q=80';
        }

        if (str_contains($cat, 'music') || str_contains($title, 'concert') || str_contains($title, 'band') || str_contains($title, 'song')) {
            return 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&q=80';
        }

        if (str_contains($cat, 'cultur') || str_contains($title, 'dance') || str_contains($title, 'drama') || str_contains($title, 'art')) {
            return 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&q=80';
        }

        if (str_contains($cat, 'annual') || str_contains($title, 'annual') || str_contains($title, 'gala')) {
            return 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&q=80';
        }

        return 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80';
    }
}
