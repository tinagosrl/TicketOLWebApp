<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->nullable()->unique(); // Subdomain or custom domain
            $table->string('email')->unique();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->boolean('is_active')->default(false); // Validated by email or manual approval
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
