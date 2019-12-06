<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public $fillable = ['name', 'quantity'];

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
