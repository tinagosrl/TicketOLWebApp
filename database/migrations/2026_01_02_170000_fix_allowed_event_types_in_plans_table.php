<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if column exists to avoid errors if partially run
        if (!Schema::hasColumn('plans', 'allowed_event_types')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->json('allowed_event_types')->nullable()->after('is_active');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('plans', 'allowed_event_types')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('allowed_event_types');
            });
        }
    }
};
