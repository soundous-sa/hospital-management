<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'cin' => 'required|string|unique:patients,cin',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'required|string',
            'adresse' => 'required|string',
            'contact_urgence' => 'required|string',
            'blood_type' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            // Créer le patient lié
            $patient = Patient::create([
                'user_id' => $user->id,
                'cin' => $request->cin,
                'date_naissance' => $request->date_naissance,
                'sexe' => $request->sexe,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'contact_urgence' => $request->contact_urgence,
                'groupe_sanguin' => $request->blood_type ?? null,
                'maladies_chroniques' => $request->chronic_diseases ?? null,
                'allergies' => $request->allergies ?? null,
            ]);

            // Générer le JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Utilisateur et patient créés avec succès',
                'user' => $user,
                'patient' => $patient,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Email ou mot de passe incorrect',
                ], 401);
            }

            $user = auth('api')->user();

            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Erreur lors de la génération du token',
            ], 500);
        }
    }
}
