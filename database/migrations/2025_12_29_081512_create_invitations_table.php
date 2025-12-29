<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('token')->unique();
            $table->string('role')->default('sub_admin'); // Default role for invited user
            $table->timestamp('expires_at');
            $table->timestamps();
            
            // Ensure unique email per tenant for pending invites
            $table->unique(['tenant_id', 'email']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
