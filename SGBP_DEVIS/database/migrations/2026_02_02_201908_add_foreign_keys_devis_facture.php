<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->foreign('facture_id')
                  ->references('id')
                  ->on('factures')
                  ->nullOnDelete();
        });
        
        Schema::table('factures', function (Blueprint $table) {
            $table->foreign('pv_id')
                  ->references('id')
                  ->on('pvs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropForeign(['facture_id']);
        });
        
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['pv_id']);
        });
    }
};
