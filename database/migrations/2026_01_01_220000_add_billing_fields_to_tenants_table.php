<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('vat_number', 20)->nullable()->after('secondary_color');
            $table->string('sdi_code', 7)->nullable()->after('vat_number');
            $table->string('pec')->nullable()->after('sdi_code');
            $table->string('address')->nullable()->after('pec');
            $table->string('city')->nullable()->after('address');
            $table->string('province', 2)->nullable()->after('city');
            $table->string('zip_code', 5)->nullable()->after('province');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'vat_number',
                'sdi_code',
                'pec',
                'address',
                'city',
                'province',
                'zip_code'
            ]);
        });
    }
};
