<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('location');
    $table->string('region');
    $table->string('image');
    $table->boolean('is_featured')->default(false);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};