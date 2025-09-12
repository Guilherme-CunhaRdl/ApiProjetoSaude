<?php

namespace App\Http\Controllers;

use App\Models\FrequenciaCardiaca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FrequenciaCardiacaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FrequenciaCardiaca::where('user_id', Auth::id())
            ->orderBy('data', 'desc')
            ->orderBy('horario', 'desc')
            ->get();
    }


    public function media($userId)
{
    $media = FrequenciaCardiaca::where('user_id', $userId)->avg('frequencia');

    return response()->json([
        'user_id' => $userId,
        'media_frequencia' => round($media, 2) // Arredondar para 2 casas
    ]);
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    public function store(Request $request)
    {
        $validated = $request->validate([
            'frequencia' => 'required|integer|min:30|max:250',
            'data' => 'required|date',
            'horario' => 'required|date_format:H:i',
            'observacao' => 'nullable|string|max:255'
        ]);

        $validated['user_id'] = Auth::id();

        return FrequenciaCardiaca::create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registro = FrequenciaCardiaca::find($id);

        if (!$registro || $registro->user_id !== Auth::id()) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $registro->delete();
        return response()->json(['message' => 'Registro removido']);
    }
}
