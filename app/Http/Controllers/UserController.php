<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function registrar(Request $request)
    {
        // Validação
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'imagem' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'debug' => [
                    'received_file' => $request->file('imagem') ? [
                        'name' => $request->file('imagem')->getClientOriginalName(),
                        'size' => $request->file('imagem')->getSize(),
                        'mime' => $request->file('imagem')->getMimeType()
                    ] : null
                ]
            ], 422);
        }
    
        try {
            // Processamento da imagem
            $imagemPath = $request->file('imagem')->store('profiles', 'public');
    
            // Criação do usuário
            $user = User::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'peso' => $request->peso,
                'altura' => $request->altura,
                'imagem_path' => $imagemPath 
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Usuário registrado com sucesso!',
                'user' => $user,
                'imagem_url' => asset($imagemPath)
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao processar imagem',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ]
        ]);
    }


            public function mostrarPerfil(Request $request)
        {
           
            $user = $request->user();
            
    

            return response()->json([
                'nome' => $user->nome,
                'email' => $user->email,
                'peso' => $user->peso,
                'altura' => $user->altura,
                'imagem_url' => $user->imagem_path 
                ? asset("storage/{$user->imagem_path}")
                : asset("storage/default-profile.png")
            ]);
        }


        public function atualizarPerfil(Request $request)  {
        

            $validated = $request->validate([
                'peso' => 'required|numeric',
                'altura' => 'required|numeric',
                'imagem' => 'nullable|mimes:jpeg,png,jpg|max:2048' 
            ]);

            $user = $request->user();

            if ($request->hasFile('imagem')) {
                $path = $request->file('imagem')->store('profiles', 'public');
                $validated['imagem_path'] = $path;
            }

            $user->update($validated);

            return response()->json([
                'peso' => $user->peso,
                'altura' => $user->altura,
                'imagem_url' => isset($validated['imagem_path']) 
                    ? asset("uploads/{$validated['imagem_path']}")
                    : null
            ]);
    }



}
