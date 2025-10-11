<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->decimal('main_oeuvre_pourcentage', 5, 2)->nullable()->after('main_oeuvre');
        });

        Schema::table('devis_categories', function (Blueprint $table) {
            $table->decimal('main_oeuvre_pourcentage', 5, 2)->nullable()->after('main_oeuvre');
        });
    }

    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn('main_oeuvre_pourcentage');
        });

        Schema::table('devis_categories', function (Blueprint $table) {
            $table->dropColumn('main_oeuvre_pourcentage');
        });
    }
};
