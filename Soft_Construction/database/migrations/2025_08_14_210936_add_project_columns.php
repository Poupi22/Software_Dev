<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // $table->string('title')->after('id');
            // $table->string('slug')->unique()->after('title');
            // $table->text('description')->nullable()->after('slug');
            // $table->string('location')->after('description');
            // $table->string('region')->after('location');
            // $table->string('category')->after('region');
            // $table->string('image')->after('category');
            // $table->boolean('is_featured')->default(false)->after('image');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'slug',
                'description',
                'location',
                'region',
                'image',
                'is_featured'
            ]);
        });
    }
};