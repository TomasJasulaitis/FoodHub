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

    public static function with($relations = []) {
        return parent::with(array_merge($relations, ['nutrients', 'ingredients', 'user']));
    }

    public function load($relations = []) {
        return parent::load(array_merge($relations, ['nutrients', 'ingredients', 'user']));
    }
}
