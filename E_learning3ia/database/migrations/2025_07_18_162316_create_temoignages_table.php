<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('temoignages', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->string('profession')->nullable();
    $table->string('photo')->nullable();
    $table->text('message');
    $table->integer('note')->nullable();
    $table->boolean('publie')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temoignages');
    }
};
