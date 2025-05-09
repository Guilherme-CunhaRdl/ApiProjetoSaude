<?php

namespace App\Http\Controllers;
use App\Models\Remedio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RemedioController extends Controller
{
    public function index()
    {
        return Remedio::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'horario' => 'required|string',
            'dosagem' => 'nullable|string',
            'frequencia' => 'nullable|string',
            'imagem' => 'nullable|string',
        ]);

        $imagemPath = null;

        if ($request->hasFile('imagem')) {
            $imagemPath = $request->file('imagem')->store('remedios', 'public');
        }

        $remedio = Remedio::create([
            'nome' => $request->nome,
            'horario' => $request->horario,
            'dosagem' => $request->dosagem,
            'frequencia' => $request->frequencia,
            'imagem' => $request->imagem,
        ]);

        return response()->json([
            'success' => true,
            'data' => $remedio,
        ], 201);
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
