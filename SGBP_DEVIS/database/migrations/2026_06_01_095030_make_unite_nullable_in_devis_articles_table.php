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
        Schema::table('devis_articles', function (Blueprint $table) {
            $table->string('unite')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('devis_articles', function (Blueprint $table) {
            $table->string('unite')->nullable(false)->change();
        });
    }
};
