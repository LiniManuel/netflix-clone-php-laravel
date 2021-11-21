<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', "description","release_year","duration"];
    protected $hidden = ['created_at', 'updated_at'];

   public static function getValidationRules() {
       return [
           'title' => ['required', 'min:3', 'max:20'],
           'description' => ['required', 'min:10', 'max:65535'],
           'release_year' => ['required', 'min:4', 'max:4'],
           'duration' => ['required', '']
       ];
   }

   public function getTitleAttribute() {
       return strtoupper($this->attributes['title']);
   }

   public function getLengthAttribute() {
       return strlen($this->attributes['description']);
   }

   public function getCommentCountAttribute() {
       return $this->comments()->count();
   }

   public function getRecentCommentsAttribute() {
       return $this
           ->comments()
           ->orderBy('created_at', 'DESC')
           ->take(5)
           ->get();
   }

   public function user() {
       return $this->belongsTo(User::class);
   }

   public function comments() {
       return $this->hasMany(Comment::class);
   }

   public function categorys() {
       return $this->belongsToMany(Category::class);
   }
}
