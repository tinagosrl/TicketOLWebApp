import os

path = '/Users/mpolsi/.gemini/antigravity/brain/TicketOL/database/migrations/2025_12_31_082509_add_features_to_plans_table.php'

if os.path.exists(path):
    with open(path, 'r') as f:
        content = f.read()

    # We want to replace the single Schema::table block with three conditional blocks.
    
    # OLD:
    # Schema::table('plans', function (Blueprint $table) {
    #     $table->integer('position')->nullable()->after('max_subadmins');
    #     $table->boolean('is_recommended')->default(false)->after('position');
    #     $table->text('features_html')->nullable()->after('description');
    # });
    
    # NEW:
    # if (!Schema::hasColumn('plans', 'position')) {
    #    Schema::table('plans', function (Blueprint $table) {
    #        $table->integer('position')->nullable()->after('max_subadmins');
    #    });
    # }
    # if (!Schema::hasColumn('plans', 'is_recommended')) { ... }
    
    new_up_logic = """
        if (!Schema::hasColumn('plans', 'position')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->integer('position')->nullable()->after('max_subadmins');
            });
        }

        if (!Schema::hasColumn('plans', 'is_recommended')) {
            Schema::table('plans', function (Blueprint $table) {
                // If position exists, put after it. If not (weird), put after max_subadmins? 
                // We just verified/created position above, so safe to use position.
                $table->boolean('is_recommended')->default(false)->after('position');
            });
        }

        if (!Schema::hasColumn('plans', 'features_html')) {
             Schema::table('plans', function (Blueprint $table) {
                $table->text('features_html')->nullable()->after('description');
             });
        }
    """
    
    # Using regex to replace the content of up() method
    import re
    
    # Match content between public function up(): void { ... }
    # Be careful with braces.
    
    # Simpler approach: Match the specific Schema::table block we wrote.
    # It has 3 specific lines.
    
    target_block_start = "Schema::table('plans', function (Blueprint $table) {"
    lines_to_match = [
        "$table->integer('position')->nullable()->after('max_subadmins');",
        "$table->boolean('is_recommended')->default(false)->after('position');",
        "$table->text('features_html')->nullable()->after('description');"
    ]
    
    if target_block_start in content:
        # We will just replace the whole file content for safety/simplicity since we know the file structure well? 
        # No, better to search/replace.
        
        # Construct the exact string to replace
        old_block = """        Schema::table('plans', function (Blueprint $table) {
            $table->integer('position')->nullable()->after('max_subadmins');
            $table->boolean('is_recommended')->default(false)->after('position');
            $table->text('features_html')->nullable()->after('description');
        });"""
        
        if old_block in content:
            content = content.replace(old_block, new_up_logic)
            with open(path, 'w') as f:
                f.write(content)
            print("Migration patched to be safe/idempotent.")
        else:
            # Try looser match (whitespace variations)
            print("Could not match exact block. Checking manually...")
            # We can rewrite the file completely since we know what it should extend.
            
            complete_file = """<?php

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
"""
            with open(path, 'w') as f:
                f.write(complete_file)
            print("Migration file rewritten completely.")

