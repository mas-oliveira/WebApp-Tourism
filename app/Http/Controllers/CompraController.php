<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Compra;
use App\Models\Pacote;
use Illuminate\Http\Request;
use App\Repositories\CompraRepository;

class CompraController extends Controller
{
    public function __construct(Compra $compra) {
        $this->compra = $compra;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $compraRepository = new PacoteRepository($this->compra);

        if($request->has('atributos_compra')) {
            $atributos_compra = 'compra:id,'.$request->compra;
            $compraRepository->selectAtributosRegistrosRelacionados($atributos_compra);
        }

        if($request->has('filtro')) {
            $compraRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $compraRepository->selectAtributos($request->atributos);
        } 

        return response()->json($compraRepository->getResultado(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->compra->rules(), $this->compra->feedback());

        $user = AuthController::getUserById($request->user_id);
        $user_pacote = AuthController::getUserById($request->pacote_user_id); //quem anunciou o pacote

        $pacote = PacoteController::getPacoteById($request->pacote_id);

        if($pacote == null) {
            return response()->json(['error' => 'Pacote a comprar não encontrado'], 404);
        }

        if ($user!=null && $user_pacote!=null && $pacote->vagas > 0) {
            $pacote->vagas = $pacote->vagas - $request->quantidade;
            $pacote->save();
            $user->num_compras = $user->num_compras + $request->quantidade;
            $user->save();

            $user_pacote->num_vendas = $user_pacote->num_vendas + $request->quantidade;
            $user_pacote->save();
    
            $compra = $this->compra->create([
                'user_id' => $user->id,
                'pacote_id' => $pacote->id,
                'metodo_pagamento' => $request->metodo_pagamento,
                'quantidade' => $request->quantidade,
                'preco_total' => $request->preco_total
            ]);
    
            return response()->json($compra, 200);
        } else {
            return response()->json(['error' => 'Não há vagas disponíveis para este pacote'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compra = $this->compra->find($id);

        if($compra === null) {
            return response()->json(['erro' => 'Recurso não encontrado'], 404);
        }

        return response()->json($compra, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    public function getComprasPorUsuario($userId)
    {
        $compras = Compra::where('user_id', $userId)->get();
        return response()->json($compras);
    }

    public function getPacotesCompradosPorUsuario($userId)
    {
        $compras = Compra::where('user_id', $userId)->get();

        $pacotes = [];
        foreach ($compras as $compra) {
            // Assume que Pacote é o nome do teu modelo para pacotes
            // e que 'pacote_id' é o nome da coluna na tabela de compras que liga a compra ao pacote
            $pacote = Pacote::find($compra->pacote_id);
            if ($pacote) {
                $pacotes[] = $pacote;
            }
        }

        return response()->json($pacotes);
    }



}