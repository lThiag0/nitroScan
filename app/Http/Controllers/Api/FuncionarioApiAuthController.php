<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Funcionario;

class FuncionarioApiAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $funcionario = Funcionario::where('email', $request->email)->first();

            if (!$funcionario || !Hash::check($request->password, $funcionario->password)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Credenciais inválidas.',
                ], 401);
            }

            $token = $funcionario->createToken('app-token')->plainTextToken;

            return response()->json([
                'status'  => 'success',
                'message' => 'Login realizado com sucesso.',
                'user'    => $funcionario,
                'token'   => $token,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $funcionario = $request->user();

        if ($funcionario) {
            $funcionario->tokens()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Logout realizado com sucesso.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não autenticado.',
        ], 401);
    }

    public function me(Request $request)
    {
        $funcionario = $request->user();

        if ($funcionario) {
            return response()->json([
                'status' => 'success',
                'user'   => $funcionario,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não autenticado.',
        ], 401);
    }
}
