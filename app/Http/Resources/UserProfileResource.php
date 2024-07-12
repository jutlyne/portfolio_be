<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->profile?->fullname,
            'username' => $this->username,
            'email' => $this->email,
            'birth_date' => $this->profile->birth_date,
            'avatar' => $this->profile->avatar
        ];
    }
}
