<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->text('adresse');
            $table->string('telephone', 50);
            $table->string('email', 100);
            $table->string('whatsapp', 255);
            $table->text('iframe_localisation');
            $table->string('facebook_link')->nullable();
            $table->string('tiktok_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};