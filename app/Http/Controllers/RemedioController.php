<?php

namespace App\Http\Controllers;
use App\Models\Remedio;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RemedioController extends Controller
{
    public function index()
    {
        return Remedio::all();
    }

    public function insertRemedio(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'nome' => 'required|string',
        'dosagem' => 'nullable|string',
        'horario' => 'required|string',
        'frequencia' => 'nullable|string',
        'imagem_path' => 'nullable|file|image|max:2048', 
    ]);

    if ($request->hasFile('imagem_path')) {
        $path = $request->file('imagem_path')->store('remedios', 'public');
        $validated['imagem_path'] = asset("/public/uploads/" . $path);
    }

    $remedio = Remedio::create($validated);

    return response()->json($remedio, 201);
}




    public function destroy($id)
    {
        $remedio = Remedio::find($id);
        
        if ($remedio) {
            // Aqui pode excluir a imagem associada ao remédio se necessário
            if ($remedio->imagem) {
                Storage::delete('public/remedios/' . $remedio->imagem);
            }
    
            $remedio->delete();
            return response()->json(['success' => true], 200);
        }
    
        return response()->json(['success' => false], 404);
    }
}
