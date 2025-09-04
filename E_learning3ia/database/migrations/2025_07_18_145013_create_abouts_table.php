<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->string('mission')->nullable();
            $table->string('vision')->nullable();
            $table->text('valeurs')->nullable(); // Peut stocker du JSON ou du texte
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('lien')->nullable();
            $table->enum('statut', ['actif', 'brouillon'])->default('brouillon');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('abouts');
    }
};