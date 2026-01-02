<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('plans', 'max_venues')) {
            Schema::table('plans', function (Blueprint $table) {
                // Default 1 venue (safe default) or 0 for unlimited? Plan said 1.
                // Using 1 as reasonable default.
                $table->integer('max_venues')->default(1)->after('ticket_limit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('plans', 'max_venues')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('max_venues');
            });
        }
    }
};
