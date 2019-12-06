<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'preparation_time' => $this->preparation_time,
            'calories' => $this->calories,
            'number_of_servings' => $this->number_of_servings,
            'image_url' => $this->image_url,
            'directions' => $this->directions,
            'ingredients' => IngredientsResource::collection($this->whenLoaded('ingredients')),
            'nutrients' => NutrientsResource::collection($this->whenLoaded('nutrients')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
