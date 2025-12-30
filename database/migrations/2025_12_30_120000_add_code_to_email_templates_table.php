<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('code')->unique()->after('id')->nullable();
        });
        
        // Populate codes for existing records if any, using name as fallback or placeholder
        // Since we are seeding, we might just drop data or handle it. 
        // For dev environment, we assume we can fill it.
    }

    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
