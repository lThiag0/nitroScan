<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Funcionario;

class FuncionarioAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-funcionario');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('funcionario')->attempt($credentials)) {
            return redirect()->intended(route('cadastro-produtos.index'));
        }

        return back()->with('error', 'Credenciais inválidas.');
    }

    public function logout()
    {
        Auth::guard('funcionario')->logout();
        return redirect()->route('funcionario.login.form');
    }
}

