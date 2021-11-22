<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model {
    protected $fillable = ['name', 'surname', 'email', 'password'];
    protected $hidden = ['password'];

    public function setPasswordAttribute($value) {
        $hash = Hash::make($value, [
            'rounds' => 14,
        ]);


        $this->attributes['password'] = $hash;
    }

    public function getMoviesCountAttribute() {
        return $this->movies()->count();
    }

    public function getRecentMovies() {
        return $this->movies()
            ->select(['id', 'title', 'created_at'])
            ->orderBy('created_at', 'DESC')
            ->take(5)
            ->get();
    }

    public static function getValidationRules($user = null) {
        return [
            'name' => ['required', 'max:100'],
            'surname' => ['required', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:250',
                'unique:App\Models\User,email' . ($user ? (',' . $user->id) : '')
            ],
            'password' => ['required', 'min:8'],
        ];
    }
}
