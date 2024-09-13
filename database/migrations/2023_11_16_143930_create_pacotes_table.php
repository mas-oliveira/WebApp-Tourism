<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacotes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome', 40);
            $table->string('descricao', 300);
            $table->string('destino', 40);
            $table->date('inicio');
            $table->date('termino');
            $table->boolean('acomodacao');
            $table->float('preco');
            $table->integer('vagas');
            $table->string('foto_capa', 100)->comment('Imagens do post');
             
            $table->integer('estado');
            /*
                Chaves dos vários estados:
                    0 => Por acontecer,
                    1 => A acontecer,
                    2 => Acabou,
                    3 => Cancelado

            */

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacotes');
    }
}
