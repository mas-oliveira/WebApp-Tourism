<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreatePublicacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacaos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('imagens')->default(new Expression('(JSON_ARRAY())'))->comment('Imagens do post')->nullable();
            $table->integer('num_likes')->default(0)->nullable();
            $table->json('user_likes')->default(new Expression('(JSON_ARRAY())'))->nullable();
            $table->json('comments')->default(new Expression('(JSON_ARRAY())'))->nullable(); 
            
            /*
                [
                    {"comment": "Gostei muito da comida", "id": 1},
                    {"comment": "O prato principal era pequeno", "id": 453}
                    ...
                ]
            */
            $table->string('titulo', 40);
            $table->string('descricao', 1000);

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
        Schema::dropIfExists('publicacaos');
    }
}
