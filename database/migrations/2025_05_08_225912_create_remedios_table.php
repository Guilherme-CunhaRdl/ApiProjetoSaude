<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remedios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('dosagem')->nullable();
            $table->string('horario'); 
            $table->string('frequencia')->default('Diário'); 
            $table->longText('imagem')->nullable(); 
            $table->timestamps(); 
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remedios');
    }
};
