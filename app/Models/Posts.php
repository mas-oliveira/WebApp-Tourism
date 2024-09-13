<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = ['num_likes', 'imagem', 'comments', 'titulo'];

    public function rules() {
        return [
            'titulo' => 'required|min:5|max:40',
            'imagem' => 'file|mimes:png'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
            'titulo.min' => 'O titulo deve ter no mínimo 5 caracteres',
            'titulo.max' => 'O titulo deve ter no máximo 40 caracteres'
        ];
    }

    public function user() {
        //UM post PERTENCE a UMA user
        return $this->belongsTo('App\Models\User');
    }
}
