<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
        // solo per amministratori
        public function getAllUsers(Request $request) {
            if ($request->auth->admin === 0) {
                return response([
                    'error' => 'Unauthorized',
                    'info' => 'Non sei autorizzato a vedere la lista degli utenti'
                ], 403);
            }
    
            $search = $request->input('search');

            $users = User::query();
    
            foreach (['name', 'surname', 'email'] AS $field) {
                $users = $users->orWhere($field, 'LIKE', '%' . $search . '%');
            }
    
            return $users->paginate(10);
        }
    
        // solo per amministratori o utente corrente
        public function get(Request $request, $id) {
            $user = User::findOrFail($id);
    
            if ($request->auth->admin === 0) {
                $user->hidden[] = 'admin';
            }
    
            $user->appends = ['movies_count', 'recent_movies'];
    
            return $user;
        }
    
        public function getAuthenticatedUser(Request $request) {
            return $request->auth;
        }

    
        public function createUser(Request $request) {
    
            $this->validate($request, User::getValidationRules());
    
            $data = $request->input();
            $user = new User($data);
            $user->save();
    
            return $user->fresh();
        }
    
        // solo per amministratori o utente corrente
        public function updateUser(Request $request, $id) {
            $user = User::findOrFail($id);
    
            if ($user->id !== $request->auth->id && $request->auth->admin === 0) {
                return response([
                    'error' => 'Unauthorized',
                    'info' => 'Non puoi modificare questo utente'
                ], 403);
            }
    
            $this->validate($request, User::getValidationRules($user));
    
            $data = $request->input();
            $user->fill($data);
            $user->save();
    
            $user->refresh();
    
            if ($request->auth->admin === 0) {
                $user->hidden[] = 'admin';
            }
    
            return $user;
        }
    
        // solo per amministratori (o utente corrente)
        public function deleteUser(Request $request, $id) {
            $user = User::findOrFail($id);
    
            if ($user->id !== $request->auth->id && $request->auth->admin === 0) {
                return response([
                    'error' => 'Unauthorized',
                    'info' => 'Non puoi eliminare questo utente'
                ], 403);
            }
                $user->delete();
    
                return [];
            }
    

        }
