<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use URL;
use App\Models\User;
use App\Http\Resources\UserResource;


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
        // return parent::toArray($request);
        return [
            'title'=>$this->title,
            'slug'=>$this->slug,
            'body'=>$this->body,
            'user'=>new UserResource(User::findorFail($this->user_id)),
            'image'=>URL::to('/').'/storage/'.str_replace('public/','',$this->image_path),
            
        ];
    }
}
