<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('lieu');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('type_evenement')->nullable();
            $table->string('image')->nullable();
            $table->string('statut')->default('brouillon');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evenements');
    }
};