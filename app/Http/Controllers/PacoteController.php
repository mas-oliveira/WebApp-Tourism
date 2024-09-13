<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Pacote;
use Illuminate\Http\Request;
use App\Repositories\PacoteRepository;

class PacoteController extends Controller
{
    public function __construct(Pacote $pacote) {
        $this->pacote = $pacote;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pacoteRepository = new PacoteRepository($this->pacote);

        if($request->has('atributos_pacote')) {
            $atributos_publicacao = 'pacote:id,'.$request->pacote;
            $pacoteRepository->selectAtributosRegistrosRelacionados($atributos_publicacao);
        }

        if($request->has('filtro')) {
            $pacoteRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $pacoteRepository->selectAtributos($request->atributos);
        } 

        $resultado = $pacoteRepository->getResultado();
        $resultadoOrdenado = $resultado->sortBy(['estado', 'data_inicio']);

        return response()->json($resultadoOrdenado->values()->all(), 200);
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
       // dd($request->destinoPacote);
        $request->validate($this->pacote->rules(), $this->pacote->feedback());
        $imagem = $request->file('foto_capa');
        $imagem_urn = $imagem->store('imagens/pacotes', 'public');

        $pacote = $this->pacote->create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'inicio' => $request->inicio,
            'termino' => $request->termino,
            'acomodacao' => $request->acomodacao,
            'destino' => $request->destino,
            'preco' => $request->preco,
            'vagas' => $request->vagas,
            'foto_capa' => $imagem_urn,
            'estado' => $request->estado,
            'user_id' => $request->user_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pacote = Pacote::find($id);

        if($pacote === null) {
            return response()->json(['erro' => 'Recurso não encontrado'], 404);
        }

        return response()->json($pacote, 200);
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

    //metodo interno usado pelo compracontroller dai nao ser uma request que chega
    public function getPacoteById($id) {
        $pacote = Pacote::find($id); 

        if($pacote != null) {
            return $pacote;
        } else {
            return null;
        }

    }

    public function changePacoteStatus(Request $request) {
        $request->validate([
            'user_id' => 'required|integer',
            'estado' => 'required|integer',
            'id_pacote' => 'required|integer'
        ]);

        $body = $request->all(['user_id', 'estado', 'id_pacote']);

        $pacote = $this->pacote->find($body['id_pacote']);

        if (!$pacote) {
            return response()->json(['error' => 'Pacote não encontrado'], 404);
        }

        $pacote->estado = $body['estado'];
        $pacote->save();

        return response()->json($pacote, 200);
    }

    public function getPacotesPorUsuario($userId)
    {
        $pacotes = Pacote::where('user_id', $userId)->get();
        return response()->json($pacotes);
    }

    public function getPacotesFeed($request) {
        $pacotes = Pacote::where('estado', $request)->orderBy('inicio', 'asc')->get();
        return response()->json($pacotes);
    }
}
