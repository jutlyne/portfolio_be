<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class UserProfile extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'fullname',
        'birth_date',
        'avatar',
    ];

    /**
     * Define a belongsTo relationship with the UserProfile model.
     *
     * This indicates that this model belongs to a UserProfile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * Get the user's first name.
     */
    public function avatar(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                return !empty($value) ? Storage::disk()->url($value) : '';
            },
        );
    }
}
