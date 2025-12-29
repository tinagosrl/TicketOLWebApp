<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Welcome Email', 'Ticket Confirmation'
            $table->string('subject_en');
            $table->text('body_en'); // HTML content
            $table->string('subject_it')->nullable();
            $table->text('body_it')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
