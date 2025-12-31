<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'features_html']);
        });
        
        Schema::table('plans', function (Blueprint $table) {
            $table->json('name')->after('id');
            $table->json('description')->nullable()->after('slug');
            $table->json('features_html')->nullable()->after('is_recommended');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'features_html']);
        });

        Schema::table('plans', function (Blueprint $table) {
             $table->string('name')->after('id');
             $table->text('description')->nullable()->after('slug');
             $table->text('features_html')->nullable()->after('is_recommended');
        });
    }
};