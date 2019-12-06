<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nutrient extends Model
{
    public $fillable = ['name', 'quantity', 'recipe_id'];

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
