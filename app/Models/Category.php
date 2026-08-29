<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public static function getDefaultCategories(): array
    {
        return [
            [
                'name' => 'Cultural Events',
                'description' => 'Dance, music, drama, fashion shows, and artistic performance competitions.',
                'icon' => 'palette'
            ],
            [
                'name' => 'Technical Fests',
                'description' => 'Hackathons, coding challenges, robotics competitions, and tech symposiums.',
                'icon' => 'code'
            ],
            [
                'name' => 'Sports Meets',
                'description' => 'Track & field events, football, basketball, cricket, and indoor sports tournaments.',
                'icon' => 'trophy'
            ],
            [
                'name' => 'Annual Day Functions',
                'description' => 'College anniversary, prize distribution, and annual celebration functions.',
                'icon' => 'star'
            ],
            [
                'name' => 'Workshops & Seminars',
                'description' => 'Academic lectures, industry expert talks, skill-building workshops, and webinars.',
                'icon' => 'book'
            ],
            [
                'name' => 'Intercollegiate Competitions',
                'description' => 'Multi-college tournaments, debates, quizzes, and inter-university fests.',
                'icon' => 'users'
            ],
            [
                'name' => 'Hackathons & Coding',
                'description' => '24-hour programming hackathons, algorithmic sprints, and app development competitions.',
                'icon' => 'laptop-code'
            ],
            [
                'name' => 'Gaming & Esports',
                'description' => 'Competitive campus esports leagues, LAN tournaments, and gaming championships.',
                'icon' => 'gamepad'
            ],
            [
                'name' => 'Music & Concerts',
                'description' => 'Live campus bands, DJ nights, classical concerts, and acoustic showcases.',
                'icon' => 'music'
            ],
            [
                'name' => 'Career & Placement Fairs',
                'description' => 'Industry recruitment drives, internship showcases, and alumni networking sessions.',
                'icon' => 'briefcase'
            ],
        ];
    }

    public static function seedDefaults(): void
    {
        foreach (self::getDefaultCategories() as $cat) {
            self::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon']
                ]
            );
        }
    }
}
