<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'post_by' => $this->post_by?\App\Models\User::findOrFail($this->post_by)->name:'Anonymous',
            'status' => $this->status,
            'category'=>$this->category_id?\App\Models\Category::findOrFail($this->category_id)->name:'Uncategorised',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
