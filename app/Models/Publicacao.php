<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacao extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descricao', 'imagens', 'user_id', 'num_likes', 'user_likes', 'comments'];

    public function rules() {
        return [
            'titulo' => 'required|min:5|max:40',
            'descricao' => 'required',
            'imagens' => 'required|array',
            'imagens.*' => 'file|mimes:png,jpeg,jpg',
            'user_id' => 'required'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagens.*.mimes' => 'Cada arquivo deve ser uma imagem do tipo PNG, JPEG ou JPG',
            'titulo.min' => 'O titulo deve ter no mínimo 5 caracteres',
            'titulo.max' => 'O titulo deve ter no máximo 40 caracteres'
        ];
    }

    public function user() {
        //UM post PERTENCE a UMA user
        return $this->belongsTo('App\Models\User');
    }
}
