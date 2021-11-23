<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends BaseController {

    public function getAllActors(Request $request) {
        $search = $request->input('search');

        $actor = Actor::query();

        foreach (['name', 'surname'] as $field) {
            $actor = $actor->orWhere($field, 'LIKE', '%' . $search . '%');
        }

        return $actor->paginate(10);
    }

    public function get($id) {
        return Actor::with('movies')->findOrFail($id);
    }

    public function createActor(Request $request) {

        if ($request->auth->admin === 0) {
            return response()->json([
                'error' => 'You are not authorized to create a new actor'
            ], 403);
        }

        $this->validate($request, Actor::getValidationRules());

        $data = $request->input();
        $actor = new Actor($data);
        $actor->save();

        return $actor->fresh();
    }

    public function editActor(Request $request, $id) {
        $actor = Actor::findOrFail($id);

        if ($request->auth->admin === 0) {
            return response()->json([
                'error' => 'You are not authorized to edit this actor'
            ], 403);
        }

        $this->validate($request, Actor::getValidationRulesUpdate());

        $data = $request->input();
        $actor->fill($data);
        $actor->save();

        return $actor->fresh();
    }

    public function deleteActor(Request $request, $id) {
        $actor = Actor::findOrFail($id);

        if ($request->auth->admin === 0) {
            return response([
                'error' => 'Unauthorized',
                'info' => 'You are not authorized to delete this actor'
            ], 403);
        }

        $actor->delete();

        return [];
    }
}