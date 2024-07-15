<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tags = [];
        foreach ($this->tags as $key => $value) {
            $tags[] = $value->name;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_text' => $this->short_text,
            'body' => $this->when(
                $this->body,
                $this->body
            ),
            'image' => $this->image,
            'tags' => $tags,
            'tag_resource' => TagResource::collection($this->tags),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
