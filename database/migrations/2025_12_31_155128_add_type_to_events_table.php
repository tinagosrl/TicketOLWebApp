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
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->default('scheduled')->after('slug'); 
        });
        
        // Update existing events to 'open' if they are Museum Entry? 
        // User asked for "Museo delle Cere" to have both, but existing ones were Open.
        // But default 'scheduled' is safer.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
