<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Str;

/**
 * Class Blog.
 *
 * @package namespace App\Models;
 */
class Blog extends BaseModel implements Transformable
{
    use TransformableTrait, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_text',
        'body',
        'views',
        'read_minutes',
        'image',
    ];

    /**
     * Define a belongsTo relationship with the User model.
     *
     * This indicates that this model belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a belongsToMany relationship with the Tag model.
     *
     * This indicates that this model has one Tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags');
    }

    /**
     * Get the blog's slug.
     */
    public function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::slug($value, '-'),
        );
    }

    /**
     * Get the blog's image.
     */
    public function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return !empty($value) ? Storage::disk()->url($value) : '';
            },
        );
    }
}
