<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class PatientController extends Controller
{
    // Middleware JWT pour sécuriser toutes les routes de ce controller
    

    /**
     * Liste tous les patients
     */
    public function index()
    {
        $patients = Patient::with('user')->get(); // inclut les infos de l'utilisateur lié
        return response()->json($patients, 200);
    }

    /**
     * Créer un nouveau patient + utilisateur
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'cin' => 'required|string|unique:patients',
            'date_naissance' => 'required|date',
            'sexe' => 'required|string',
            'telephone' => 'required|string',
            'adresse' => 'required|string',
            'contact_urgence' => 'required|string',
            'blood_type' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user', // rôle fixe pour les patients
            ]);

            // Créer le patient lié à l'utilisateur
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

            return response()->json([
                'message' => 'Utilisateur et patient créés avec succès',
                'user' => $user,
                'patient' => $patient
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un patient
     */
    public function show($id)
    {
        $patient = Patient::with('user')->find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient non trouvé'], 404);
        }
        return response()->json($patient, 200);
    }

    /**
     * Mettre à jour un patient
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $patient->user_id,
            'password' => 'sometimes|string|min:6',
            'cin' => 'sometimes|string|unique:patients,cin,' . $patient->id,
            'date_naissance' => 'sometimes|date',
            'sexe' => 'sometimes|string',
            'telephone' => 'sometimes|string',
            'adresse' => 'sometimes|string',
            'contact_urgence' => 'sometimes|string',
            'blood_type' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $user = $patient->user;
            if ($request->has('name')) $user->name = $request->name;
            if ($request->has('email')) $user->email = $request->email;
            if ($request->has('password')) $user->password = Hash::make($request->password);
            $user->save();

            $patient->update([
                'cin' => $request->cin ?? $patient->cin,
                'date_naissance' => $request->date_naissance ?? $patient->date_naissance,
                'sexe' => $request->sexe ?? $patient->sexe,
                'telephone' => $request->telephone ?? $patient->telephone,
                'adresse' => $request->adresse ?? $patient->adresse,
                'contact_urgence' => $request->contact_urgence ?? $patient->contact_urgence,
                'groupe_sanguin' => $request->blood_type ?? $patient->groupe_sanguin,
                'maladies_chroniques' => $request->chronic_diseases ?? $patient->maladies_chroniques,
                'allergies' => $request->allergies ?? $patient->allergies,
            ]);

            return response()->json(['message' => 'Patient mis à jour', 'patient' => $patient], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer un patient
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient non trouvé'], 404);
        }

        try {
            $patient->user()->delete(); // supprime également l'utilisateur lié
            $patient->delete();
            return response()->json(['message' => 'Patient supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()], 500);
        }
    }
}
