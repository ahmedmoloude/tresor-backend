<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Le champ nom est requis.',
            'email.required' => 'Le champ email est requis.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'L\'adresse email est déjà utilisée.',
            'phone.required' => 'Le champ téléphone est requis.',
            'phone.unique' => 'Le numéro de téléphone est déjà utilisé.',
            'password.required' => 'Le champ mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Le champ email est requis.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'password.required' => 'Le champ mot de passe est requis.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $auth = Auth::attempt($request->only('email', 'password'));
        
        if (!$auth) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $user = Auth::user();

        if ($user->account_disabled == true) {
            return response()->json(['message' => 'Votre compte a été désactivé'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }


    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté avec succès'], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        
        if ($user->account_disabled == true) {
            return response()->json(['message' => 'Votre compte a été désactivé'], 401);
        }

        return response()->json(['user' => $user], 200);
    }
}
