<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Check if status exists, if not add it
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->string('status')->default('valid')->after('unique_code');
            }
            
            // Check if remaining_uses exists (just in case)
            if (!Schema::hasColumn('tickets', 'remaining_uses')) {
                // Add remaining_uses after status (if we just added it or if it existed)
                $table->integer('remaining_uses')->default(1)->after('unique_code'); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'remaining_uses')) {
                $table->dropColumn('remaining_uses');
            }
            if (Schema::hasColumn('tickets', 'status')) {
                 $table->dropColumn('status');
            }
        });
    }
};
