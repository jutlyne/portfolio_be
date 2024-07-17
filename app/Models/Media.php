<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Media.
 *
 * @package namespace App\Models;
 */
class Media extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'mime_type',
        'type',
    ];

    /**
     * Get the media url.
     */
    public function url(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return !empty($value) ? Storage::disk()->url($value) : '';
            },
        );
    }

}
