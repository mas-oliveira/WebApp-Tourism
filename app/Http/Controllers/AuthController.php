<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        
        $credenciais = $request->all(['email', 'password']); //[]

        //autenticação (email e senha)
        $token = auth('api')->attempt($credenciais);
        
        if($token) { //usuário autenticado com sucesso
            return response()->json(['token' => $token]);

        } else { //erro de usuário ou senha
            return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);

            //401 = Unauthorized -> não autorizado
            //403 = forbidden -> proibido (login inválido)
        }

        //retornar um Json Web Token
        return 'login';
    }

    public function logout() {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso!']);
    }

    public function refresh() {
        $token = auth('api')->refresh(); //cliente encaminhe um jwt válido
        return response()->json(['token' => $token]);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    public function myUserId() {
        return response()->json(auth()->id());
    }

    public function myUserImage() {
        return response()->json([
            'profile_pic' => auth()->user()->profile_pic,
            'nome' => auth()->user()->name
        ]);
    }

    public function myName(){
        return response()->json([
            'nome' => auth()->user()->name,
            'email' => auth()->user()->email
        ]);
    }

    //function that gets an id via parameter and returns the name of the user, and the profile pic :)
    public function getUserInfo($request){
        $user = User::find($request);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json([
            'nome' => $user->name,
            'email' => $user->email,
            'profile_pic' => $user->profile_pic,
            'num_compras' => $user->num_compras,
            'num_vendas' => $user->num_vendas
        ]);
    }

    public function getUserById($id) {
        $user = User::find($id); 

        if($user != null) {
            return $user;
        } else {
            return null;
        }
    }

    public function changeProfilePic(Request $request)
{
    $imagem = $request->file('imagem');

    $request->validate([
        'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
    ]);

    $imagem_urn = $imagem->store('imagens/profile_pics', 'public');
    // Obtém o usuário autenticado
    $user = auth()->user();

    // Atualiza a foto do perfil do usuário
    $user->profile_pic = $imagem_urn;
    $user->save();

    return response()->json(['message' => 'Foto do perfil atualizada com sucesso!'], 200);
}

    
}
