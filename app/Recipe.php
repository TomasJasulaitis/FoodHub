<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function nutrients() {
        return $this->hasMany(Nutrient::class);
    }

    public function ingredients() {
        return $this->hasMany(Ingredient::class);
    }
}
