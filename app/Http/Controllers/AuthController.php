<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Affiche la page de login
     */
    public function showLogin()
    {
        // Si déjà connecté, pas besoin de revoir le login
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Traite le formulaire de login
     */
    public function login(Request $request)
    {
        // Étape 1 : Valider les champs du formulaire
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        // Étape 2 : Tenter la connexion avec email + password
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Étape 3 : Régénérer la session pour plus de sécurité
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard')
                             ->with('success', 'Bienvenue ' . Auth::user()->name . ' !');
        }

        // Étape 4 : Échec de connexion → retour au formulaire avec erreur
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalider complètement la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Vous êtes déconnecté.');
    }
}