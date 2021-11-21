<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    public static function getValidationRules() {
        return [
            'name' => ['required', 'max:100']
        ];
    }

    public function movies() {
        return $this->belongsToMany(Movie::class);
    }
}
