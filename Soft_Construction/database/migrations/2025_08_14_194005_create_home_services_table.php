<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('home_services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('feature_title_1');
            $table->text('feature_description_1');
            $table->string('feature_icon_1');
            $table->string('feature_title_2');
            $table->text('feature_description_2');
            $table->string('feature_icon_2');
            $table->string('feature_title_3');
            $table->text('feature_description_3');
            $table->string('feature_icon_3');
            $table->string('button_text')->default('Demander un service');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_services');
    }
};