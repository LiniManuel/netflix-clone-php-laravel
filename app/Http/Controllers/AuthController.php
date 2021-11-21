<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function login(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['error' => 'Credenziali non valide'], 401);
        }

        $payload = [
            'iss' => 'lumen-blog',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (60 * 60)
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'));

        return ['token' => $token];
    }

    public function test(Request $request) {
        return $request->auth;
        
        return 'funziona!!!';
    }
}
