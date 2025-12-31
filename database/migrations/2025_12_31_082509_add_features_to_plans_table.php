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
        if (!Schema::hasColumn('plans', 'position')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->integer('position')->nullable()->after('max_subadmins');
            });
        }

        if (!Schema::hasColumn('plans', 'is_recommended')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('is_recommended')->default(false)->after('position');
            });
        }

        if (!Schema::hasColumn('plans', 'features_html')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->text('features_html')->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['position', 'is_recommended', 'features_html']);
        });
    }
};
