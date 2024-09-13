<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use App\Repositories\PublicacaoRepository;


class PublicacaoController extends Controller
{
    public function __construct(publicacao $publicacao) {
        $this->publicacao = $publicacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $publicacaoRepository = new PublicacaoRepository($this->publicacao);

        if($request->has('atributos_publicacao')) {
            $atributos_publicacao = 'publicacao:id,'.$request->atributos_publicacao;
            $publicacaoRepository->selectAtributosRegistrosRelacionados($atributos_publicacao);
        }

        if($request->has('filtro')) {
            $publicacaoRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $publicacaoRepository->selectAtributos($request->atributos);
        } 

        return response()->json($publicacaoRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->publicacao->rules(), $this->publicacao->feedback());

        // Processar cada imagem enviada
        $imagens = $request->file('imagens');
        $imagens_urns = [];
        foreach ($imagens as $imagem) {
            $imagens_urns[] = $imagem->store('imagens/posts', 'public');
        }

        $publicacao = $this->publicacao->create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'imagens' => json_encode($imagens_urns), // Armazenar as URNs das imagens como uma string JSON
            'user_id' => $request->user_id
        ]);

        return response()->json($publicacao, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @param  int  $pubId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $publicacao = $this->publicacao->where('id', $id)->first();

        if (!$publicacao) {
            return response()->json(['error' => 'Publicação não encontrada'], 404);
        }
        if ($publicacao->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Ação não autorizada'], 403);
        }

        $publicacao->delete();

        return response()->json(['success' => 'Publicação apagada com sucesso'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(Request $request)
    {   
        $request->validate([
            'id' => 'required|integer',
            'comentario' => 'required|array',
            'comentario.comment' => 'required|string',
            'comentario.user_id' => 'required|integer'
        ]);

        $body = $request->all(['id', 'comentario']);

        $publicacao = $this->publicacao->find($body['id']);

        if (!$publicacao) {
            return response()->json(['error' => 'Publicação não encontrada'], 404);
        }

        if ($publicacao->comments === null) {
            $publicacao->comments = [];
        } else {
            $publicacao->comments = json_decode($publicacao->comments, true);
        }

        $publicacao->comments = array_merge($publicacao->comments, [$body['comentario']]);
        $publicacao->comments = json_encode($publicacao->comments);
        $publicacao->save();

        return response()->json($publicacao, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeComment(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'comentario_index' => 'required|integer'
        ]);

        $body = $request->all(['id', 'comentario_index']);

        $publicacao = $this->publicacao->find($body['id']);

        if (!$publicacao) {
            return response()->json(['error' => 'Publicação não encontrada'], 404);
        }

        if ($publicacao->comments === null) {
            return response()->json(['error' => 'Não há comentários para remover'], 404);
        } else {
            $publicacao->comments = json_decode($publicacao->comments, true);
        }

        if ($body['comentario_index'] < 0 || $body['comentario_index'] >= count($publicacao->comments)) {
            return response()->json(['error' => 'Índice de comentário inválido'], 400);
        }

        $comments = $publicacao->comments;
        array_splice($comments, $body['comentario_index'], 1);
        $publicacao->comments = json_encode($comments);

        $publicacao->save();

        return response()->json($publicacao, 200);
    }


    public function getComments(Request $request) {
        $request->validate([
            'id' => 'required|integer']);
        
        $body = $request->all(['id']);

        $publicacao = $this->publicacao->find($body['id']);

        if (!$publicacao) {
            return response()->json(['error' => 'Publicação não encontrada'], 404);
        }

        return response()->json($publicacao->comments,200);
    }


    public function addLike(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);
    
        $body = $request->all(['id', 'user_id']);
    
        $publicacao = $this->publicacao->find($body['id']);
    
        if (!$publicacao) {
            return response()->json(['error' => 'Publicação não encontrada'], 404);
        }
    
        if ($publicacao->user_likes === null) {
            $publicacao->user_likes = [];
        } else {
            $publicacao->user_likes = json_decode($publicacao->user_likes, true);
        }
    
        if (!in_array($body['user_id'], $publicacao->user_likes)) {
            $publicacao->user_likes = array_merge($publicacao->user_likes, [$body['user_id']]);
            $publicacao->num_likes += 1; 
        }
    
        $publicacao->user_likes = json_encode($publicacao->user_likes);
        $publicacao->save();
    
        return response()->json($publicacao, 200);
    }
    
    public function removeLike(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);
    
        $body = $request->all(['id', 'user_id']);
    
        $publicacao = $this->publicacao->find($body['id']);
    
        if (!$publicacao) {
            return response()->json(['error' => 'Publicação não encontrada'], 404);
        }
    
        if ($publicacao->user_likes !== null) {
            $publicacao->user_likes = json_decode($publicacao->user_likes, true);
            if (in_array($body['user_id'], $publicacao->user_likes)) {
                $publicacao->user_likes = array_diff($publicacao->user_likes, [$body['user_id']]);
                $publicacao->num_likes -= 1; 
            }
            $publicacao->user_likes = json_encode($publicacao->user_likes);
        }
    
        $publicacao->save();
    
        return response()->json($publicacao, 200);
    }

}


