<?php

namespace App\Http\Controllers;
use App\Models\Remedio;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RemedioController extends Controller
{
    public function index(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }


        $userId = Auth::id();
        
   
        return Remedio::where('user_id', $userId)->get();
    }

    public function insertRemedio(Request $request)
    {
    
        $validated = $request->validate([
            'nome' => 'required|string',
            'dosagem' => 'nullable|string',
            'horario' => 'required|string',
            'frequencia' => 'nullable|string',
            'imagem_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

 
        $validated['user_id'] = Auth::id();

 
        if ($request->hasFile('imagem_path')) {
            $path = $request->file('imagem_path')->store('remedios', 'public');
            $validated['imagem_path'] = $path;
        }

        $remedio = Remedio::create($validated);

        return response()->json($remedio, 201);
    }

    public function destroy($id)
    {
        $remedio = Remedio::find($id);
        

        if (!$remedio || $remedio->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Remédio não encontrado ou não autorizado'], 404);
        }

    
        if ($remedio->imagem_path && file_exists(public_path($remedio->imagem_path))) {
            unlink(public_path($remedio->imagem_path));
        }

        $remedio->delete();
        return response()->json(['success' => true], 200);
    }
}
