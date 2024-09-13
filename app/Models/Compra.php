<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pacote_id',
        'metodo_pagamento',
        'quantidade',
        'preco_total'
    ];

    public function rules() {
        return [
            'user_id' => 'required',
            'pacote_id' => 'required',
            'metodo_pagamento' => 'required',
            'quantidade' => 'required',
            'preco_total' => 'required'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
     
    /**
     * Get the user that owns the compra.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pacote that owns the compra.
     */
    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}
