<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Movie;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function getAllMovies(Request $request) {
        $search = $request->input('search');

        $movies = Movie::query();

        foreach (['title', 'description', 'release_year','duration'] AS $field) {
            $movies = $movies->orWhere($field, 'LIKE', '%' . $search . '%');
        }

        return $movies->paginate(10);
    }

    public function get($id) {
        $movies = Movie::with('user')->with('categorys')->findOrFail($id);
        return $movies;
    }

    public function getUserMovies($userId) {
        $user = User::findOrFail($userId);
        return $user->movies;
    }

    public function createMovie(Request $request) {
        // $title = $request->input('title');
        // $content = $request->input('content');

        $this->validate($request, Movie::getValidationRules());

        $data = $request->input();
        $movie = new Movie($data);

        $movie->user()->associate($request->auth);

        $movie->save();

        $movie->categorys()->sync([1, 2, 3]);

        return $movie->fresh();
    }

    public function updateMovie(Request $request, $id) {
        $movie = Movie::findOrFail($id);

        if ($request->auth->admin === 0) {
            return response([
                'error' => 'Unauthorized',
                'info' => 'Non sei il proprietario del film'
            ], 403);
        }

        $this->validate($request, Movie::getValidationRules());

        $data = $request->input();
        $movie->fill($data);
        $movie->save();

        return $movie->fresh();
    }

    public function deleteMovie(Request $request, $id) {
        $movie = Movie::findOrFail($id);

        if ($request->auth->id === 0) {
            return response([
                'error' => 'Unauthorized',
                'info' => 'Non sei il proprietario del film'
            ], 403);
        }

        $movie->delete();

        return [];
    }

}
