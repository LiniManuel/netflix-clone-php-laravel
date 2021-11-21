<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model {
    protected $fillable = ['name', 'surname', 'birthday'];
    protected $hidden = [''];
    public $timestamps = false;

    public static function getValidationRules() {
        return [
            'name' => ['required', 'max:100'],
            'surname' => ['required', 'max:100'],
            'birthday' => ['required', 'date']
        ];
    }

    public static function getValidationRulesUpdate() {
        return [
            'name' => ['max:100'],
            'surname' => ['max:100'],
            'birthday' => ['date']
        ];
    }

    public function getMoviesAttribute() {
        $movies = $this->movies()->take(5)->get();
        return $movies;
    }

    public function movies() {
        return $this->belongsToMany(Movie::class);
    }
}