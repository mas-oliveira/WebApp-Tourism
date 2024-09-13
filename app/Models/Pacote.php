<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacote extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'destino', 'inicio', 'termino', 'acomodacao', 'preco', 'vagas', 'estado', 'foto_capa', 'user_id'];

    public function rules() {
        return [
            'nome' => 'required|min:5|max:40',
            'descricao' => 'required|min:20|max:300',
            'destino' => 'required|min:5|max:40',
            'inicio' => 'required',
            'termino' => 'required',
            'acomodacao' => 'required',
            'preco' => 'required',
            'foto_capa' => 'file|mimes:jpg,jpeg,png',
            'vagas' => 'required|min:0',
            'user_id' => 'required'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
            'nome.min' => 'O titulo deve ter no mínimo 5 caracteres',
            'nome.max' => 'O titulo deve ter no máximo 40 caracteres',
            'descricao.min' => 'A descricao deve ter no mínimo 20 caracteres',
            'descricao.max' => 'A descricao deve ter no máximo 300 caracteres',
            'destino.min' => 'O destino deve ter no mínimo 5 caracteres',
            'destino.max' => 'O destino deve ter no máximo 40 caracteres',
            'vagas.min' => 'Deve ter no minimo 0 vagas ne?'
        ];
    }
}
