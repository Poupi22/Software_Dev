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
        Schema::create('cours_instance_formateur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_instance_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->comment('ID du formateur')->constrained()->onDelete('cascade');
            $table->string('role_pedagogique')->default('Principal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_instance_formateur');
    }
};
